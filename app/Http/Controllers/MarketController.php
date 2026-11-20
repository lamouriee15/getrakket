<?php

namespace App\Http\Controllers;

use App\Models\JobPost;
use App\Models\Message;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MarketController extends Controller
{
    private const MIMES = [
        'image/jpeg' => 'image', 'image/png' => 'image', 'image/webp' => 'image', 'image/gif' => 'image',
        'video/mp4' => 'video', 'video/webm' => 'video', 'video/quicktime' => 'video',
        'audio/mpeg' => 'audio', 'audio/mp4' => 'audio', 'audio/x-m4a' => 'audio', 'audio/wav' => 'audio',
        'audio/x-wav' => 'audio', 'audio/webm' => 'audio', 'audio/ogg' => 'audio',
        'application/pdf' => 'pdf',
    ];

    private function bad($m, $c = 400)
    {
        return response()->json(['error' => $m], $c);
    }

    private function str(Request $r, string $k, int $max = 0): string
    {
        $v = trim((string) $r->input($k));
        return $max ? mb_substr($v, 0, $max) : $v;
    }

    private function mine(Request $r, $id)
    {
        return JobPost::where('id', $id)->where('client_id', $r->user()->id)->first();
    }

    private function msg($from, $to, string $body): void
    {
        Message::create(['sender_id' => $from, 'receiver_id' => $to, 'body' => $body]);
    }

    private function maxUpload(): int
    {
        $u = ini_parse_quantity((string) ini_get('upload_max_filesize'));
        $p = ini_parse_quantity((string) ini_get('post_max_size'));
        $m = min($u > 0 ? $u : PHP_INT_MAX, $p > 0 ? $p : PHP_INT_MAX, 60 * 1048576);
        return max(0, $m - 300000);
    }

    private function ice(): array
    {
        $servers = [['urls' => ['stun:stun.l.google.com:19302', 'stun:stun1.l.google.com:19302', 'stun:stun.cloudflare.com:3478']]];
        $urls = array_values(array_filter(array_map('trim', explode(',', (string) config('getrakket.turn_urls')))));
        if ($urls && config('getrakket.turn_username') && config('getrakket.turn_credential')) {
            $servers[] = ['urls' => $urls, 'username' => config('getrakket.turn_username'), 'credential' => config('getrakket.turn_credential')];
        }
        return $servers;
    }

    public function state()
    {
        $id = Auth::id();
        if (!$id) return ['me' => null, 'users' => [], 'jobs' => [], 'chats' => [], 'reviews' => [], 'limit' => 0, 'ice' => [], 'csrf' => csrf_token()];

        $sk = DB::table('expertise')->get()->groupBy('user_id')->map(fn($g) => $g->pluck('skill')->values());
        $ap = DB::table('applications')
            ->select('job_post_id', 'freelancer_id', 'note', 'sample_kind', 'sample_name', 'sample_link')
            ->get()->groupBy('job_post_id')
            ->map(fn($g) => $g->map(fn($a) => [
                'id' => (string) $a->freelancer_id, 'note' => (string) $a->note, 'kind' => $a->sample_kind,
                'name' => $a->sample_name, 'link' => $a->sample_link,
            ])->values());

        return [
            'me' => (string) $id,
            'csrf' => csrf_token(),
            'limit' => $this->maxUpload(),
            'ice' => $this->ice(),
            'users' => User::select('id', 'name', 'bio', 'has_pic', 'pic_v')->get()->map(fn($u) => [
                'id' => (string) $u->id, 'name' => $u->name, 'bio' => (string) $u->bio,
                'pic' => $u->has_pic ? "/api/users/{$u->id}/pic?v={$u->pic_v}" : '',
                'expertise' => $sk->get($u->id, collect())->values(),
            ])->values(),
            'jobs' => JobPost::orderBy('id')->get()->map(fn($j) => [
                'id' => (string) $j->id, 'title' => $j->title, 'desc' => $j->description, 'budget' => $j->budget,
                'cat' => $j->category, 'by' => (string) $j->client_id, 'apps' => $ap->get($j->id, collect())->values(),
                'hired' => $j->hired_id ? (string) $j->hired_id : null, 'status' => $j->status,
            ])->values(),
            'reviews' => Review::orderBy('id')->get()->map(fn($v) => [
                'id' => (string) $v->id, 'jobId' => (string) $v->job_post_id, 'to' => (string) $v->freelancer_id,
                'by' => (string) $v->client_id, 'stars' => (int) $v->stars, 'text' => $v->comment,
            ])->values(),
            'chats' => Message::where('sender_id', $id)->orWhere('receiver_id', $id)->orderBy('id')->get()->map(fn($m) => [
                'id' => (string) $m->id, 'from' => (string) $m->sender_id, 'to' => (string) $m->receiver_id,
                'text' => $m->body, 't' => $m->created_at->valueOf(),
            ])->values(),
        ];
    }

    public function pic($id)
    {
        $u = User::where('id', $id)->where('has_pic', true)->first(['id', 'pic']);
        abort_unless($u, 404);
        return response(base64_decode(explode(',', $u->pic, 2)[1]), 200, [
            'Content-Type' => 'image/jpeg',
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }

    public function profile(Request $r)
    {
        $u = $r->user();
        $name = $this->str($r, 'name', 60);
        if ($name === '') return $this->bad('Name can’t be empty.');
        $pic = $r->input('pic');
        if ($pic !== null && !(is_string($pic) && strlen($pic) < 500000 && preg_match('#^data:image/jpeg;base64,[A-Za-z0-9+/=]+$#', $pic))) {
            return $this->bad('That photo couldn’t be used.');
        }
        $u->name = $name;
        $u->bio = $this->str($r, 'bio', 300);
        if ($r->boolean('removePic')) {
            $u->pic = null;
            $u->has_pic = false;
            $u->pic_v = $u->pic_v + 1;
        } elseif ($pic !== null) {
            $u->pic = $pic;
            $u->has_pic = true;
            $u->pic_v = $u->pic_v + 1;
        }
        $u->save();
        return ['ok' => 1];
    }

    public function expertise(Request $r)
    {
        $u = $r->user();
        $skills = collect($r->input('skills', []))->map(fn($s) => mb_substr(trim((string) $s), 0, 40))->filter()->unique()->take(40)->values();
        DB::transaction(function () use ($u, $skills) {
            DB::table('expertise')->where('user_id', $u->id)->delete();
            DB::table('expertise')->insert($skills->map(fn($s) => ['user_id' => $u->id, 'skill' => $s])->all());
        });
        return ['ok' => 1];
    }

    public function postJob(Request $r)
    {
        $t = $this->str($r, 'title', 120);
        $d = $this->str($r, 'desc', 2000);
        $c = $this->str($r, 'cat', 40);
        if ($t === '' || $d === '' || $c === '') return $this->bad('Add a title, a category and a description.');
        JobPost::create([
            'client_id' => $r->user()->id, 'title' => $t, 'description' => $d, 'category' => $c,
            'budget' => $this->str($r, 'budget', 40) ?: 'Open',
        ]);
        return ['ok' => 1];
    }

    public function apply(Request $r, $id)
    {
        $j = JobPost::find($id);
        if (!$j) return $this->bad('Job not found.', 404);
        $u = $r->user();
        if ($j->client_id === $u->id) return $this->bad('You can’t apply to your own job.');
        if ($j->status !== 'open') return $this->bad('This job is closed.');
        if (DB::table('applications')->where('job_post_id', $j->id)->where('freelancer_id', $u->id)->exists()) {
            return $this->bad('You already applied to this job.');
        }

        $row = ['job_post_id' => $j->id, 'freelancer_id' => $u->id, 'note' => $this->str($r, 'note', 500)];
        $f = $r->file('sample');
        if ($f) {
            if (!$f->isValid()) return $this->bad('The upload failed or the file is too large. Paste a link instead.');
            $mime = (string) $f->getMimeType();
            if (!isset(self::MIMES[$mime])) return $this->bad('Upload a photo, MP4 video, audio file or PDF.');
            if ($f->getSize() > $this->maxUpload()) return $this->bad('That file is too large for this server. Paste a link instead.');
            $row += [
                'sample_kind' => self::MIMES[$mime], 'sample_mime' => $mime,
                'sample_name' => mb_substr($f->getClientOriginalName(), 0, 200),
                'sample_data' => base64_encode(file_get_contents($f->getRealPath())),
            ];
        } else {
            $link = $this->str($r, 'link', 500);
            if (!preg_match('#^https?://\S+$#i', $link)) return $this->bad('Add a work sample: upload a file or paste a link starting with http:// or https://');
            $row += ['sample_kind' => 'link', 'sample_link' => $link];
        }

        DB::table('applications')->insert($row);
        $this->msg($u->id, $j->client_id, "Hi! I applied for “{$j->title}” and attached a work sample.");
        return ['ok' => 1];
    }

    public function sample(Request $r, $id, $uid)
    {
        $j = JobPost::find($id);
        abort_unless($j, 404);
        $me = $r->user()->id;
        abort_unless($me === $j->client_id || $me === (int) $uid, 403);
        $a = DB::table('applications')->where('job_post_id', $j->id)->where('freelancer_id', (int) $uid)->first();
        abort_unless($a && $a->sample_data, 404);
        $tmp = tempnam(sys_get_temp_dir(), 'smp');
        file_put_contents($tmp, base64_decode($a->sample_data));
        return response()->file($tmp, [
            'Content-Type' => $a->sample_mime,
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, max-age=3600',
        ])->deleteFileAfterSend(true);
    }

    public function hire(Request $r, $id)
    {
        $j = $this->mine($r, $id);
        if (!$j) return $this->bad('Job not found.', 404);
        $f = (int) $r->input('freelancerId');
        if ($j->status !== 'open') return $this->bad('This job already has someone hired.');
        if (!DB::table('applications')->where('job_post_id', $j->id)->where('freelancer_id', $f)->exists()) {
            return $this->bad('That person hasn’t applied.');
        }
        $j->update(['status' => 'hired', 'hired_id' => $f]);
        $this->msg($r->user()->id, $f, "You’re hired for “{$j->title}”.");
        return ['ok' => 1];
    }

    public function progress(Request $r, $id)
    {
        $j = JobPost::find($id);
        if (!$j || $j->hired_id !== $r->user()->id) return $this->bad('Job not found.', 404);
        $to = (string) $r->input('status');
        $ok = ($j->status === 'hired' && in_array($to, ['working', 'delivered'], true)) || ($j->status === 'working' && $to === 'delivered');
        if (!$ok) return $this->bad('That status change isn’t allowed.');
        $j->update(['status' => $to]);
        $this->msg($r->user()->id, $j->client_id, $to === 'working'
            ? "I started working on “{$j->title}”."
            : "I delivered “{$j->title}”. Please review it and mark it as done.");
        return ['ok' => 1];
    }

    public function done(Request $r, $id)
    {
        $j = $this->mine($r, $id);
        if (!$j) return $this->bad('Job not found.', 404);
        if (!in_array($j->status, ['hired', 'working', 'delivered'], true)) return $this->bad('Hire someone first.');
        $j->update(['status' => 'done']);
        return ['ok' => 1];
    }

    public function review(Request $r, $id)
    {
        $j = $this->mine($r, $id);
        if (!$j) return $this->bad('Job not found.', 404);
        if ($j->status !== 'done') return $this->bad('Mark the job as done before rating.');
        $stars = (int) $r->input('stars');
        if ($stars < 1 || $stars > 5) return $this->bad('Pick 1 to 5 stars.');
        if (Review::where('job_post_id', $j->id)->exists()) return $this->bad('You already rated this job.');
        Review::create([
            'job_post_id' => $j->id, 'freelancer_id' => $j->hired_id, 'client_id' => $r->user()->id,
            'stars' => $stars, 'comment' => $this->str($r, 'text', 500),
        ]);
        return ['ok' => 1];
    }

    public function message(Request $r)
    {
        $to = (int) $r->input('to');
        $t = $this->str($r, 'text', 2000);
        if ($t === '' || $to === Auth::id() || !User::whereKey($to)->exists()) return $this->bad('Message can’t be sent.');
        $this->msg(Auth::id(), $to, $t);
        return ['ok' => 1];
    }

    public function signals(Request $r)
    {
        $id = $r->user()->id;
        User::where('id', $id)
            ->where(fn($q) => $q->whereNull('last_seen_at')->orWhere('last_seen_at', '<', now()->subSeconds(3)))
            ->update(['last_seen_at' => now()]);
        $rows = DB::transaction(function () use ($id) {
            $rows = DB::table('call_signals')->where('to_id', $id)->orderBy('id')->get();
            if ($rows->isNotEmpty()) DB::table('call_signals')->whereIn('id', $rows->pluck('id'))->delete();
            return $rows;
        });
        return ['signals' => $rows->map(fn($s) => ['type' => $s->type, 'from' => (string) $s->from_id, 'data' => $s->payload])->values()];
    }

    public function signal(Request $r)
    {
        $type = (string) $r->input('type');
        if (!in_array($type, ['offer', 'answer', 'ice', 'hangup'], true)) return $this->bad('Bad signal.');
        $peer = User::find((int) $r->input('to'));
        if (!$peer) return $this->bad('User not found.', 404);
        DB::table('call_signals')->where('created_at', '<', now()->subMinute())->delete();
        if ($type === 'offer' && (!$peer->last_seen_at || $peer->last_seen_at->lt(now()->subSeconds(15)))) {
            return ['offline' => true];
        }
        DB::table('call_signals')->insert([
            'from_id' => Auth::id(), 'to_id' => $peer->id, 'type' => $type,
            'payload' => $r->input('data'), 'created_at' => now(),
        ]);
        return ['ok' => 1];
    }
}
