<!DOCTYPE html>
<html lang="en"><head><meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="csrf-token" content="{{ csrf_token() }}">
@verbatim
<title>GetRakket: Freelance Marketplace</title>
<style>
:root{--bg:#f0f2f5;--card:#fff;--tx:#050505;--mu:#65676b;--bd:#d8dadf;--pr:#1b74e4;--lk:#1b74e4;--pr2:#e7f3ff;--bub:#eceef1;--g1:#0ea5a4;--g2:#6366f1;--st:#f5a623;box-sizing:border-box;padding-top:env(safe-area-inset-top,0px);padding-bottom:env(safe-area-inset-bottom,0px)}
@media(prefers-color-scheme:dark){:root:not([data-theme="light"]){--bg:#18191a;--card:#242526;--tx:#e4e6eb;--mu:#b0b3b8;--bd:#3e4042;--pr:#2f6fd6;--lk:#7bb6ff;--pr2:#263951;--bub:#3a3b3c}}
:root[data-theme="dark"]{--bg:#18191a;--card:#242526;--tx:#e4e6eb;--mu:#b0b3b8;--bd:#3e4042;--pr:#2f6fd6;--lk:#7bb6ff;--pr2:#263951;--bub:#3a3b3c}
html{scroll-padding-top:env(safe-area-inset-top,0px)}
*{box-sizing:border-box}html,body{height:100%;margin:0}
body{background:var(--bg);color:var(--tx);font:15px/1.45 "Segoe UI",Helvetica,Arial,sans-serif}
body::before{content:"";position:fixed;inset:0 0 auto;height:env(safe-area-inset-top,0px);background:var(--card);z-index:10}
[hidden]{display:none!important}
button,input,select,textarea{font:inherit;color:inherit}
input,select,textarea{width:100%;padding:10px 12px;border:1px solid var(--bd);border-radius:8px;background:var(--card);margin:0 0 10px}
input[type=radio]{width:auto;margin:0 4px 0 0}textarea{resize:vertical}
:focus-visible{outline:2px solid var(--lk);outline-offset:2px}
h2,h3,h4{margin:0 0 8px}
.btn{border:0;border-radius:8px;padding:9px 14px;font-weight:600;cursor:pointer;background:var(--bub)}
.btn.p{background:var(--pr);color:#fff}.btn.w{width:100%}
.lk{background:none;border:0;padding:0;cursor:pointer;color:var(--lk);font-weight:600;text-align:left}
.card{background:var(--card);border-radius:12px;box-shadow:0 1px 2px rgba(0,0,0,.2)}
.sp{flex:1}.row{display:flex;align-items:center;gap:8px;flex-wrap:wrap}.pad{padding:14px}
.mu,small{color:var(--mu)}.ok{color:#188038}.err{color:#d93025;margin:0}
.empty{color:var(--mu);padding:24px;text-align:center}
.nav{position:sticky;top:env(safe-area-inset-top,0px);z-index:9;display:flex;align-items:center;gap:10px;flex-wrap:wrap;padding:8px 16px;background:var(--card);box-shadow:0 1px 2px rgba(0,0,0,.2)}
.logo{font-size:24px;color:var(--lk)}
.nav nav{display:flex;gap:4px}.nav nav button,.tabs button{padding:8px 14px;border-radius:8px;border:0;background:none;cursor:pointer;color:var(--mu);font-weight:600}
.nav nav .on,.tabs .on{background:var(--pr2);color:var(--lk)}
.wrap{max-width:1100px;margin:16px auto;padding:0 16px}
.auth{min-height:100%;display:grid;grid-template-columns:1fr 400px;gap:40px;align-items:center;max-width:960px;margin:auto;padding:24px}
.hero h1{color:var(--lk);font-size:56px;margin:0}.hero p{font-size:20px;max-width:420px}
.auth form{padding:20px}.auth a,.auth .lk{display:block;text-align:center;margin:10px 0}
.seg{display:flex;gap:16px;margin-bottom:10px}
.mk{display:grid;grid-template-columns:280px 1fr;gap:16px;align-items:start}
.side{padding:16px;position:sticky;top:76px}
.tabs{display:flex;gap:6px;margin-bottom:12px}
.grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(230px,1fr));gap:14px}
.item{overflow:hidden}.item .pad p{margin:6px 0;min-height:44px}
.cover{height:84px;display:grid;place-items:center}
.av{display:inline-grid;place-items:center;width:36px;height:36px;border-radius:50%;color:#fff;font-weight:700;flex:none;object-fit:cover}
.av.lg{width:64px;height:64px;font-size:26px;border:3px solid var(--card)}
.av.xl{width:96px;height:96px;font-size:40px;border:4px solid var(--card)}
.stars i{font-style:normal;color:var(--bd);letter-spacing:1px}.stars i.on{color:var(--st)}
.chips{display:flex;flex-wrap:wrap;gap:6px;margin:8px 0}
.chip{padding:4px 10px;border-radius:99px;background:var(--pr2);color:var(--lk);font-size:13px;border:0}
button.chip{cursor:pointer;background:var(--bub);color:var(--tx);padding:6px 12px}
button.chip:not(.on)::before{content:"+ "}button.chip.on{background:var(--pr);color:#fff}button.chip.on::before{content:"✓ "}
.tag{font-size:12px;padding:1px 8px;border-radius:99px;background:var(--bub);color:var(--mu)}
.job{padding:14px;margin-bottom:12px}.jb{margin-top:10px;border-top:1px solid var(--bd);padding-top:10px}.jb .row{margin-bottom:6px}
.prof{overflow:hidden}.pcov{height:150px}
.ph{display:flex;align-items:flex-end;gap:16px;padding:0 20px;margin-top:-48px;flex-wrap:wrap}
.cols{display:grid;grid-template-columns:1fr 1fr;gap:20px;padding:20px}
.rv{display:flex;gap:10px;margin:12px 0}.rv p{margin:2px 0}
.chat{display:grid;grid-template-columns:300px 1fr;height:calc(100dvh - 120px);min-height:420px;overflow:hidden}
.cl{overflow:auto;border-right:1px solid var(--bd);padding:12px}
.conv{display:flex;gap:10px;width:100%;padding:10px;border:0;border-radius:10px;background:none;cursor:pointer;text-align:left;align-items:center}
.conv.on{background:var(--pr2)}.conv span{display:flex;flex-direction:column;min-width:0}
.ch{display:flex;flex-direction:column;min-width:0}
.chh{display:flex;align-items:center;gap:10px;padding:10px 14px;border-bottom:1px solid var(--bd)}
.back{display:none}
.msgs{flex:1;overflow:auto;padding:16px;display:flex;flex-direction:column;gap:6px}
.b{max-width:72%;padding:8px 12px;background:var(--bub);border-radius:4px 16px 16px 16px;align-self:flex-start;overflow-wrap:anywhere}
.b small{display:block;font-size:11px}
.b.me{align-self:flex-end;background:linear-gradient(135deg,var(--g1),var(--g2));color:#fff;border-radius:16px 4px 16px 16px}.b.me small{color:#e8eaff}
.comp{display:flex;gap:8px;padding:10px;border-top:1px solid var(--bd)}.comp input{margin:0;border-radius:99px}
.ov{position:fixed;inset:0;background:rgba(0,0,0,.5);display:grid;place-items:center;z-index:40;padding:16px}
.md{width:min(540px,100%);max-height:90vh;overflow:auto;padding:20px}.md .row{justify-content:flex-end}
.pick button{background:none;border:0;font-size:34px;color:var(--bd);cursor:pointer;padding:0 2px}.pick button.on{color:var(--st)}
#call{position:fixed;inset:0;z-index:50;background:#101418;color:#fff}
.cw{height:100%;display:grid;place-items:center;text-align:center;padding:20px}.cw p{max-width:340px;color:#c9ced6}
#cv{position:absolute;right:16px;bottom:96px;width:170px;height:120px;object-fit:cover;border-radius:12px;background:#222;transform:scaleX(-1)}
.ctl{position:absolute;bottom:calc(20px + env(safe-area-inset-bottom,0px));left:0;right:0;display:flex;justify-content:center;gap:10px}
.ctl button{border:0;border-radius:99px;padding:12px 20px;font-weight:600;cursor:pointer;background:#2c333b;color:#fff}.ctl .end{background:#d93025}
@media(max-width:800px){.auth,.mk,.cols{grid-template-columns:1fr}.side{position:static}.hero h1{font-size:40px}
.chat{grid-template-columns:1fr}.chat.open .cl,.chat:not(.open) .ch{display:none}.back{display:inline-block}.b{max-width:85%}}
#rv{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;background:#101418;display:none}#cv{z-index:2}.ctl{z-index:3}</style></head><body>
<div id="app"></div><div id="call" hidden></div>
<script>
const $=s=>document.querySelector(s),esc=s=>String(s??"").replace(/[&<>"]/g,c=>({"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;"}[c]));
const CATS={"Editing":["Video editing","Photo editing","Proofreading","Copy editing","Podcast editing"],
"Design":["Logo making","Digital art","Graphic design","UI/UX design","Poster and flyer design","Illustration","Thumbnail design","Canva templates"],
"Programming":["Web development","Coding","Bug fixing","Database setup","WordPress","Mobile apps"],
"Writing":["Content writing","Copywriting","Blog writing","Resume writing","Translation","Transcription"],
"Virtual assistance":["Email management","Data entry","Calendar scheduling","Customer support","Social media management","Online research","Spreadsheet work","Online tutoring"]};
let DB={users:[],jobs:[],chats:[],reviews:[]};
const S={me:null,view:"login",tab:"f",q:"",fx:"",note:"",cw:null,modal:null,rs:0,st:null,pid:null};
let CSRF=document.querySelector('meta[name="csrf-token"]').content;
async function api(m,u,b){try{const r=await fetch(u,{method:m,headers:{"Content-Type":"application/json","Accept":"application/json","X-CSRF-TOKEN":CSRF},body:b?JSON.stringify(b):undefined});const j=await r.json();if(j.csrf)CSRF=j.csrf;if(!r.ok&&!j.error)j.error=r.status==429?"Too many attempts. Try again in 15 minutes.":j.message||"Something went wrong.";return j}catch(e){return{error:"Can’t reach the server. Is it running?"}}}
async function refresh(){const r=await api("GET","/api/state");if(r.error)return;DB=r;S.me=r.me}
async function act(m,u,b){const r=await api(m,u,b);if(r.error)return r.error;await refresh();return ""}
const tell=async(...a)=>{const x=await act(...a);if(x)alert(x)};
const push=(to,text)=>act("POST","/api/messages",{to,text});
const me=()=>DB.users.find(u=>u.id===S.me),uf=id=>DB.users.find(u=>u.id===id)||{id:"x",name:"Deleted user",expertise:[],bio:""};
const hue=id=>[...id].reduce((a,c)=>a+c.charCodeAt(0),0)*37%360,tm=t=>new Date(t).toLocaleTimeString([],{hour:"numeric",minute:"2-digit"});
async function enter(r){S.me=r.me;S.pid=r.me;S.view="market";await refresh();connect();return ""}
async function signup(f){const r=await api("POST","/api/signup",f);return r.error||enter(r)}
async function login(f){const r=await api("POST","/api/login",f);if(r.error)return r.error;S.note="";return enter(r)}
async function reset(f){const r=await api("POST","/api/reset",f);if(r.error)return r.error;S.view="login";S.note="Password updated. Log in with your new password.";return ""}
const sx=n=>`<span class="stars" aria-label="${n} of 5 stars"><i class="on">${"★".repeat(n)}</i><i>${"★".repeat(5-n)}</i></span>`;
const rate=id=>{const r=DB.reviews.filter(x=>x.to===id);return{n:r.length,a:r.length?r.reduce((s,x)=>s+x.stars,0)/r.length:0}};
const stars=(a,n)=>sx(Math.round(a))+` <small>${n?a.toFixed(1)+" ("+n+(n>1?" reviews)":" review)"):"No ratings yet"}</small>`;
const av=(u,c="")=>u.pic?`<img class="av ${c}" src="${esc(u.pic)}" alt="">`:`<span class="av ${c}" style="background:hsl(${hue(u.id)} 55% 42%)">${esc(u.name[0])}</span>`;
const opts=()=>Object.entries(CATS).map(([g,l])=>`<optgroup label="${g}">${l.map(x=>`<option>${x}</option>`).join("")}</optgroup>`).join("");
const nav=()=>`<header class="nav"><b class="logo">GetRakket</b><nav>${[["market","Marketplace"],["chat","Messages"],["profile","Profile"]].map(([k,t])=>`<button data-go="${k}" class="${S.view==k?"on":""}">${t}</button>`).join("")}</nav><span class="sp"></span>${av(me())}<button class="btn" data-act="out">Log out</button></header>`;
function authV(){const v=S.view,note=S.note?`<p class="ok">${esc(S.note)}</p>`:"";
const f={login:`<h2>Log in to GetRakket</h2><input name="email" type="email" placeholder="Email" required autocomplete="email"><input name="pw" type="password" placeholder="Password" required autocomplete="current-password"><button class="btn p w">Log in</button><button type="button" class="lk" data-go="forgot">Forgot password?</button><hr><button type="button" class="btn w" data-go="signup">Create new account</button>`,
signup:`<h2>Create your account</h2><input name="name" placeholder="Full name" required><input name="email" type="email" placeholder="Email" required autocomplete="email"><input name="pw" type="password" placeholder="Password (6+ characters)" required autocomplete="new-password"><div class="seg"><label><input type="radio" name="role" value="client" checked>I want to hire</label><label><input type="radio" name="role" value="freelancer">I want to work</label></div><input name="ans" placeholder="Recovery answer: name of your first school" required><button class="btn p w">Sign up</button><button type="button" class="lk" data-go="login">Already have an account? Log in</button>`,
forgot:`<h2>Reset your password</h2><input name="email" type="email" placeholder="Email" required><input name="ans" placeholder="Recovery answer: name of your first school" required><input name="pw" type="password" placeholder="New password" required autocomplete="new-password"><button class="btn p w">Save new password</button><button type="button" class="lk" data-go="login">Back to log in</button>`}[v];
return `<div class="auth"><div class="hero"><h1>GetRakket</h1><p>Hire freelancers for design, writing, coding, tutoring and virtual assistance, or find your next project.</p></div><form class="card" data-f="${v}">${note}${f}<p class="err"></p></form></div>`}
function listHTML(){const q=S.q.toLowerCase(),fx=S.fx;
if(S.tab=="f"){const l=DB.users.filter(u=>u.role=="freelancer"&&(!fx||u.expertise.includes(fx))&&(u.name+u.bio+u.expertise.join()).toLowerCase().includes(q));
return l.length?`<div class="grid">${l.map(u=>{const r=rate(u.id);return `<div class="card item"><div class="cover" style="background:hsl(${hue(u.id)} 50% 38%)">${av(u,"lg")}</div><div class="pad"><b>${esc(u.name)}</b><div>${stars(r.a,r.n)}</div><p class="mu">${esc(u.bio||"No bio yet.")}</p><div class="chips">${u.expertise.slice(0,3).map(x=>`<span class="chip">${esc(x)}</span>`).join("")}</div><div class="row"><button class="btn" data-pid="${u.id}">View profile</button><button class="btn p" data-chat="${u.id}">Message</button></div></div></div>`}).join("")}</div>`:`<p class="empty">No freelancers match yet. Try a different expertise or search.</p>`}
const l=DB.jobs.filter(j=>(!fx||j.cat==fx)&&(j.title+j.desc).toLowerCase().includes(q)).reverse();
return l.length?l.map(jobCard).join(""):`<p class="empty">No job requests match yet.</p>`}
function jobCard(j){const m=me(),own=j.by==m.id,hf=j.hired?uf(j.hired):null,rv=DB.reviews.find(r=>r.jobId==j.id),by=uf(j.by);let a="";
if(own){if(j.status=="open")a=j.apps.length?`<small>Applicants</small>`+j.apps.map(id=>`<div class="row">${av(uf(id))}<button class="lk" data-pid="${id}">${esc(uf(id).name)}</button><span class="sp"></span><button class="btn" data-chat="${id}">Message</button><button class="btn p" data-hire="${j.id}|${id}">Hire</button></div>`).join(""):`<small>No applicants yet</small>`;
else if(j.status=="hired")a=`<div class="row">Hired: <button class="lk" data-pid="${hf.id}">${esc(hf.name)}</button><span class="sp"></span><button class="btn p" data-done="${j.id}">Mark as done</button></div>`;
else a=rv?`<div class="row">You rated ${esc(hf.name)} ${sx(rv.stars)}</div>`:`<button class="btn p" data-rate="${j.id}">Rate ${esc(hf.name)}</button>`}
else if(m.role=="freelancer")a=j.hired==m.id?`<span class="ok">You were hired for this job</span>`:j.status!="open"?`<small>Closed</small>`:j.apps.includes(m.id)?`<span class="ok">Applied</span>`:`<button class="btn p" data-apply="${j.id}">Apply</button>`;
return `<div class="card job"><div class="row"><b>${esc(j.title)}</b><span class="sp"></span><span class="tag">${j.status}</span></div><p class="mu">${esc(j.desc)}</p><div class="row"><span class="chip">${esc(j.cat)}</span><span>${esc(j.budget)}</span><span class="sp"></span><small>Posted by <button class="lk" data-pid="${by.id}">${esc(by.name)}</button></small></div>${a?`<div class="jb">${a}</div>`:""}</div>`}
const marketV=()=>`<div class="mk"><aside class="card side"><h3>Marketplace</h3><input id="q" placeholder="Search" value="${esc(S.q)}"><select id="fx"><option value="">All expertise</option>${opts()}</select>${me().role=="client"?`<button class="btn p w" data-act="jobform">+ Post a job</button>`:`<p class="mu">Apply to job requests. Add your expertise on your profile so clients can find you.</p>`}</aside><section><div class="tabs"><button data-tab="f" class="${S.tab=="f"?"on":""}">Freelancers</button><button data-tab="j" class="${S.tab=="j"?"on":""}">Job requests</button></div><div id="list">${listHTML()}</div></section></div>`;
function chatV(){const m=me(),mine=DB.chats.filter(c=>c.from==m.id||c.to==m.id),ids=[...new Set(mine.map(c=>c.from==m.id?c.to:c.from).reverse())];
if(S.cw&&!ids.includes(S.cw))ids.unshift(S.cw);
const list=ids.map(id=>{const p=uf(id),l=mine.filter(c=>c.from==id||c.to==id).pop();return `<button class="conv ${S.cw==id?"on":""}" data-chat="${id}">${av(p)}<span><b>${esc(p.name)}</b><small>${l?esc(l.text.slice(0,34)):"Say hello"}</small></span></button>`}).join("")||`<p class="empty">No conversations yet. Message a freelancer from the Marketplace.</p>`;
const p=S.cw?uf(S.cw):null,th=p?mine.filter(c=>c.from==S.cw||c.to==S.cw):[];
const main=p?`<div class="ch"><div class="chh"><button class="btn back" data-act="back">Back</button>${av(p)}<b>${esc(p.name)}</b><span class="sp"></span><button class="btn p" data-act="call">Video call</button></div><div class="msgs" id="msgs">${th.map(c=>`<div class="b ${c.from==m.id?"me":""}">${esc(c.text)}<small>${tm(c.t)}</small></div>`).join("")||'<p class="empty">No messages yet. Say hello.</p>'}</div><form class="comp" data-f="send"><input name="text" id="ti" placeholder="Write a message" autocomplete="off"><button class="btn p">Send</button></form></div>`:`<div class="ch"><p class="empty">Pick a conversation to start chatting.</p></div>`;
return `<div class="chat card ${p?"open":""}"><div class="cl"><h3>Inbox</h3>${list}</div>${main}</div>`}
function profV(){const u=uf(S.pid),own=u.id==S.me,fl=u.role=="freelancer",r=rate(u.id),rv=DB.reviews.filter(x=>x.to==u.id).reverse();
return `<div class="card prof"><div class="pcov" style="background:hsl(${hue(u.id)} 50% 38%)"></div><div class="ph">${av(u,"xl")}<div><h2>${esc(u.name)}</h2><span class="tag">${fl?"Freelancer":"Client"}</span>${fl?`<div>${stars(r.a,r.n)}</div>`:""}</div><span class="sp"></span>${own?`<button class="btn p" data-act="prof">Edit profile</button>`:`<button class="btn p" data-chat="${u.id}">Message</button>`}</div>
<div class="cols"><section><h3>About</h3><p>${esc(u.bio||(own?"Add a bio so people know who you are.":"No bio yet."))}</p></section>
${fl?`<section><div class="row"><h3>Expertise</h3><span class="sp"></span>${own?`<button class="btn" data-act="exp">Edit expertise</button>`:""}</div><div class="chips">${u.expertise.map(x=>`<span class="chip">${esc(x)}</span>`).join("")||`<p class="mu">${own?"Add your expertise so clients can find you.":"No expertise added yet."}</p>`}</div></section>`:""}</div>
${fl?`<section class="pad"><h3>Ratings and reviews</h3>${rv.map(x=>`<div class="rv">${av(uf(x.by))}<div><b>${esc(uf(x.by).name)}</b> ${sx(x.stars)}<p>${esc(x.text||"")}</p></div></div>`).join("")||`<p class="mu">No reviews yet. Ratings appear here after a client marks a job as done.</p>`}</section>`:""}</div>`}
function modalV(){const M=S.modal;if(!M)return "";let b="";
if(M.t=="job")b=`<form data-f="job"><h3>Post a job</h3><input name="title" placeholder="What do you need?" required><select name="cat">${opts()}</select><input name="budget" placeholder="Budget, e.g. ₱1,500"><textarea name="desc" rows="4" placeholder="Describe the work" required></textarea><div class="row"><button type="button" class="btn" data-close>Cancel</button><button class="btn p">Post job</button></div></form>`;
if(M.t=="exp")b=`<form data-f="exp"><h3>Choose your expertise</h3><p class="mu">Select everything you do well. Clients find you by these.</p>${Object.entries(CATS).map(([g,l])=>`<h4>${g}</h4><div class="chips">${l.map(x=>`<button type="button" class="chip ${me().expertise.includes(x)?"on":""}" data-x="${x}">${x}</button>`).join("")}</div>`).join("")}<div class="row"><button type="button" class="btn" data-close>Cancel</button><button class="btn p">Save expertise</button></div></form>`;
if(M.t=="prof"){const u=me();b=`<form data-f="prof"><h3>Edit profile</h3><div class="row" style="justify-content:flex-start"><span id="pv">${av(u,"xl")}</span><div><button type="button" class="btn" data-act="pic">Choose photo</button> <button type="button" class="btn" data-act="nopic">Remove photo</button><input type="file" id="pf" accept="image/*" hidden></div></div><h4>Name</h4><input name="name" value="${esc(u.name)}" required maxlength="60"><h4>Bio</h4><textarea name="bio" rows="4" maxlength="300" placeholder="Tell people about yourself, up to 300 characters">${esc(u.bio)}</textarea><p class="err"></p><div class="row"><button type="button" class="btn" data-close>Cancel</button><button class="btn p">Save profile</button></div></form>`}
if(M.t=="rate"){const J=DB.jobs.find(x=>x.id==M.j);b=`<form data-f="rate"><h3>Rate ${esc(uf(J.hired).name)}</h3><p class="mu">How was the work on “${esc(J.title)}”?</p><div class="pick">${[1,2,3,4,5].map(i=>`<button type="button" data-s="${i}" aria-label="${i} star${i>1?"s":""}">★</button>`).join("")}</div><textarea name="text" rows="3" placeholder="Add a short review (optional)"></textarea><p class="err"></p><div class="row"><button type="button" class="btn" data-close>Later</button><button class="btn p">Submit rating</button></div></form>`}
return `<div class="ov"><div class="card md">${b}</div></div>`}
const AUTH=["login","signup","forgot"];
function render(){const a=$("#app");
if(!S.me||!me()){S.me=null;if(!AUTH.includes(S.view))S.view="login";a.innerHTML=authV();return}
if(AUTH.includes(S.view))S.view="market";
a.innerHTML=nav()+`<main class="wrap">${S.view=="market"?marketV():S.view=="chat"?chatV():profV()}</main>`+modalV();
const fx=$("#fx");if(fx)fx.value=S.fx;const l=$("#msgs");if(l)l.scrollTop=l.scrollHeight;const ti=$("#ti");if(ti)ti.focus()}
let pc,peer,inc,pend=[],timer,last="";
const ICE={iceServers:[{urls:"stun:stun.l.google.com:19302"}]};
function connect(){if(!timer)timer=setInterval(poll,1500)}
function stop(){clearInterval(timer);timer=null;last=""}
async function poll(){if(!S.me)return;
const g=await api("GET","/api/signals");(g.signals||[]).forEach(s=>{if(s.data)s.data=JSON.parse(s.data);rtc(s)});
const r=await api("GET","/api/state");if(r.error)return;const j=JSON.stringify(r);if(j==last)return;last=j;DB=r;S.me=r.me;
if(S.modal||document.activeElement.id=="q")return;const t=$("#ti"),v=t?t.value:"";render();const n=$("#ti");if(n&&v)n.value=v}
const sig=(type,data)=>peer?api("POST","/api/signal",{to:peer,type,data:data?JSON.stringify(data):undefined}):0;
function callUI(p,msg,ring){const c=$("#call");c.innerHTML=`<div class="cw"><div>${av(p,"xl")}<h3>${esc(p.name)}</h3><p id="cs">${esc(msg)}</p></div><video id="rv" autoplay playsinline></video><video id="cv" autoplay muted playsinline ${ring?"hidden":""}></video><div class="ctl">${ring?'<button data-c="accept">Accept</button>':'<button data-c="mic">Mute</button><button data-c="cam">Camera off</button>'}<button class="end" data-c="end">${ring?"Decline":"End call"}</button></div></div>`;c.hidden=false}
async function setup(id){peer=id;const c=pc=new RTCPeerConnection(ICE);S.st=await navigator.mediaDevices.getUserMedia({video:true,audio:true});$("#cv").srcObject=S.st;S.st.getTracks().forEach(t=>c.addTrack(t,S.st));
c.ontrack=e=>{const v=$("#rv");v.srcObject=e.streams[0];v.style.display="block"};
c.onicecandidate=e=>e.candidate&&sig("ice",e.candidate);
c.onconnectionstatechange=()=>{if(pc===c&&["failed","closed"].includes(c.connectionState))endCall(false)}}
const addIce=async c=>{if(pc&&pc.remoteDescription)await pc.addIceCandidate(c);else pend.push(c)};
const flush=async()=>{for(const c of pend.splice(0))await pc.addIceCandidate(c)};
const BLOCKED="Camera or microphone is blocked. Allow access in your browser, then try again.";
async function call(id){const p=uf(id);callUI(p,"Calling "+p.name+"…");
try{await setup(id);const o=await pc.createOffer();await pc.setLocalDescription(o);const r=await sig("offer",o);
if(r&&r.offline){$("#cs").textContent="They’re not online right now. Send a message instead.";return}
push(id,"📹 Started a video call");const c=pc;setTimeout(()=>{if(pc===c&&c.connectionState=="new")endCall(true)},45000)}
catch(e){$("#cs").textContent=BLOCKED}}
async function accept(){const m=inc;inc=null;callUI(uf(m.from),"Connecting…");
try{await setup(m.from);await pc.setRemoteDescription(m.offer);await flush();const a=await pc.createAnswer();await pc.setLocalDescription(a);sig("answer",a)}
catch(e){$("#cs").textContent=BLOCKED}}
function endCall(n){if(n)sig("hangup");if(S.st)S.st.getTracks().forEach(t=>t.stop());S.st=null;if(pc){const c=pc;pc=null;c.close()}inc=null;pend=[];peer=null;$("#call").hidden=true;$("#call").innerHTML=""}
async function rtc(m){
if(m.type=="offer"){if(pc||inc)return api("POST","/api/signal",{to:m.from,type:"hangup"});inc={from:m.from,offer:m.data};peer=m.from;callUI(uf(m.from),uf(m.from).name+" is calling you",true)}
else if(m.type=="answer"&&pc){await pc.setRemoteDescription(m.data);await flush()}
else if(m.type=="ice")await addIce(m.data)
else if(m.type=="hangup"&&m.from==peer)endCall(false)}
document.addEventListener("click",async e=>{const t=e.target.closest("[data-go],[data-pid],[data-chat],[data-tab],[data-act],[data-apply],[data-hire],[data-done],[data-rate],[data-close],[data-s],[data-x],[data-c]");if(!t)return;const d=t.dataset;
if(d.s){S.rs=+d.s;document.querySelectorAll(".pick button").forEach((b,i)=>b.classList.toggle("on",i<S.rs));return}
if(d.x!=null){t.classList.toggle("on");return}
if(d.c){const s=S.st;if(d.c=="end"){endCall(true)}else if(d.c=="accept"){accept()}
else if(s){const k=d.c=="mic"?s.getAudioTracks():s.getVideoTracks();k.forEach(x=>x.enabled=!x.enabled);const off=k[0]&&!k[0].enabled;t.textContent=d.c=="mic"?(off?"Unmute":"Mute"):(off?"Camera on":"Camera off")}return}
if(d.act=="call"){call(S.cw);return}
if(d.act=="pic"){$("#pf").click();return}
if(d.act=="nopic"){S.pp="";$("#pv").innerHTML=av({...me(),pic:""},"xl");return}
if(d.go){S.view=d.go;S.note="";if(d.go=="profile")S.pid=S.me}
else if(d.pid){S.view="profile";S.pid=d.pid}
else if(d.chat){S.view="chat";S.cw=d.chat}
else if(d.tab)S.tab=d.tab
else if(d.apply)await tell("POST","/api/jobs/"+d.apply+"/apply")
else if(d.hire){const[i,u]=d.hire.split("|");await tell("POST","/api/jobs/"+i+"/hire",{freelancerId:u})}
else if(d.done){await tell("POST","/api/jobs/"+d.done+"/done");S.modal={t:"rate",j:d.done};S.rs=0}
else if(d.rate){S.modal={t:"rate",j:d.rate};S.rs=0}
else if(d.close!=null)S.modal=null
else if(d.act=="out"){await api("POST","/api/logout");S.me=null;DB={users:[],jobs:[],chats:[],reviews:[]};S.view="login";stop()}
else if(d.act=="jobform")S.modal={t:"job"}
else if(d.act=="exp")S.modal={t:"exp"}
else if(d.act=="prof"){S.modal={t:"prof"};S.pp=null}
else if(d.act=="back")S.cw=null;
render()});
document.addEventListener("submit",async e=>{e.preventDefault();const fm=e.target,k=fm.dataset.f,f=Object.fromEntries(new FormData(fm));let err="";
if(k=="login")err=await login(f);else if(k=="signup")err=await signup(f);else if(k=="forgot")err=await reset(f);
else if(k=="send"){if(f.text.trim())err=await push(S.cw,f.text.trim())}
else if(k=="prof"){if(!f.name.trim())err="Name can’t be empty.";else{err=await act("PUT","/api/profile",{name:f.name,bio:f.bio,pic:S.pp||undefined,removePic:S.pp===""});if(!err)S.modal=null}}
else if(k=="exp"){err=await act("PUT","/api/expertise",{skills:[...fm.querySelectorAll(".chip.on")].map(b=>b.dataset.x)});if(!err)S.modal=null}
else if(k=="job"){err=await act("POST","/api/jobs",f);if(!err){S.modal=null;S.tab="j";S.fx="";S.q=""}}
else if(k=="rate"){if(!S.rs)err="Pick 1 to 5 stars.";else{err=await act("POST","/api/jobs/"+S.modal.j+"/review",{stars:S.rs,text:f.text});if(!err)S.modal=null}}
if(err){const el=fm.querySelector(".err");if(el)el.textContent=err;else alert(err);return}
render()});
document.addEventListener("input",e=>{if(e.target.id=="q"){S.q=e.target.value;$("#list").innerHTML=listHTML()}});
function photo(file){if(!file||!file.type.startsWith("image/"))return;const r=new FileReader();r.onload=()=>{const im=new Image();im.onload=()=>{const c=document.createElement("canvas"),s=Math.min(im.width,im.height);c.width=c.height=256;c.getContext("2d").drawImage(im,(im.width-s)/2,(im.height-s)/2,s,s,0,0,256,256);S.pp=c.toDataURL("image/jpeg",.85);$("#pv").innerHTML=av({...me(),pic:S.pp},"xl")};im.src=r.result};r.readAsDataURL(file)}
document.addEventListener("change",e=>{if(e.target.id=="fx"){S.fx=e.target.value;$("#list").innerHTML=listHTML()}else if(e.target.id=="pf")photo(e.target.files[0])});
refresh().then(()=>{S.pid=S.me;S.view=S.me?"market":"login";render();if(S.me)connect()});
</script>
@endverbatim
</body></html>
