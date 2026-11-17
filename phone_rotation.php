<?php
// Open this page after running the SQL setup and configuring includes/connection.php.
?>
<!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Sales Phone Rotation</title>
<style>
body{font-family:Arial,sans-serif;background:#f3f5f8;margin:0;color:#172334}.wrap{max-width:850px;margin:35px auto;padding:0 15px}h1{margin-bottom:5px}.sub{color:#526273}.panel{background:white;padding:20px;margin:18px 0;border-radius:12px;box-shadow:0 2px 12px #0001}table{width:100%;border-collapse:collapse}td,th{padding:12px;text-align:left;border-bottom:1px solid #e4e9ee}tr:first-child td{background:#eaf6ed}button{border:0;border-radius:7px;padding:9px 13px;cursor:pointer;background:#1769aa;color:white}button:disabled{opacity:.5;cursor:default}.secondary{background:#697785}.danger{background:#a33b3b}input{padding:10px;border:1px solid #bac5ce;border-radius:7px}form{display:flex;gap:8px;flex-wrap:wrap}.msg{min-height:20px;color:#176a35}.error{color:#a11}.small{font-size:13px;color:#536070}
</style></head><body><main class="wrap"><h1>Sales Phone Rotation</h1><p class="sub">Only the salesperson who takes the call moves to the bottom.</p>
<div id="message" class="msg" role="status"></div>
<section class="panel"><h2>Call Order</h2><table><thead><tr><th>Order</th><th>Salesperson</th><th>Action</th></tr></thead><tbody id="rotation"><tr><td colspan="3">Loading...</td></tr></tbody></table></section>
<section class="panel"><h2>Add Salesperson</h2><form id="addForm"><input id="name" maxlength="120" required placeholder="Salesperson name"><button type="submit">Add to Bottom</button></form></section>
<section class="panel"><h2>Inactive Salespeople</h2><p class="small">Use this for people who leave the rotation. Do not deactivate someone just because they are temporarily absent.</p><div id="inactive"></div></section>
<section class="panel"><h2>Last 20 Assigned Calls</h2><table><thead><tr><th>Salesperson</th><th>Time</th></tr></thead><tbody id="history"></tbody></table></section>
</main><script>
const rotation=document.getElementById('rotation'),inactive=document.getElementById('inactive'),historyBody=document.getElementById('history'),message=document.getElementById('message');
function el(tag,text){const node=document.createElement(tag);node.textContent=text;return node;}
async function api(action,params={}){
 const data=new FormData();data.append('action',action);Object.entries(params).forEach(([k,v])=>data.append(k,v));
 const response=await fetch('phone_api.php',{method:'POST',body:data,credentials:'same-origin'});
 const result=await response.json();if(!response.ok||!result.success)throw Error(result.message||'Request failed');return result;
}
function render(data){
 rotation.replaceChildren();inactive.replaceChildren();historyBody.replaceChildren();let order=0;
 for(const p of data.salespeople){
  if(Number(p.active)===1){order++;const tr=document.createElement('tr');tr.append(el('td',String(order)),el('td',p.name));
   const td=document.createElement('td'),button=el('button','Assign Call');button.onclick=()=>change('assign',{id:p.id},'Assign this call to '+p.name+'?');td.append(button);tr.append(td);rotation.append(tr);
  }else{const line=document.createElement('p');line.append(document.createTextNode(p.name+' '));const button=el('button','Reactivate');button.className='secondary';button.onclick=()=>change('set_active',{id:p.id,active:1});line.append(button);inactive.append(line);}
 }
 if(!order){const tr=document.createElement('tr');const td=el('td','No active salespeople. Add someone below.');td.colSpan=3;tr.append(td);rotation.append(tr);}
 if(!inactive.childNodes.length)inactive.append(el('p','None'));
 for(const h of data.history){const tr=document.createElement('tr');tr.append(el('td',h.salesperson_name),el('td',h.assigned_at));historyBody.append(tr);}
 if(!data.history.length){const tr=document.createElement('tr');const td=el('td','No calls assigned yet');td.colSpan=2;tr.append(td);historyBody.append(tr);}
 // A manager can deactivate an active salesperson via the button below.
 const section=document.getElementById('manageActive');if(section)section.remove();
 const div=document.createElement('div');div.id='manageActive';div.className='panel';div.append(el('h2','Manage Active Salespeople'));
 for(const p of data.salespeople.filter(p=>Number(p.active)===1)){const line=document.createElement('p');line.append(document.createTextNode(p.name+' '));const b=el('button','Deactivate');b.className='danger';b.onclick=()=>change('set_active',{id:p.id,active:0},'Deactivate '+p.name+'?');line.append(b);div.append(line);}
 document.querySelector('main').append(div);
}
async function change(action,params,question){if(question&&!confirm(question))return;message.textContent='Saving...';message.className='msg';try{const data=await api(action,params);render(data);message.textContent=data.message;}catch(e){message.textContent=e.message;message.className='msg error';}}
document.getElementById('addForm').addEventListener('submit',async e=>{e.preventDefault();const name=document.getElementById('name');await change('add',{name:name.value});if(!message.classList.contains('error'))name.value='';});
async function refresh(){try{render(await api('list'));}catch(e){message.textContent=e.message;message.className='msg error';}}
refresh();setInterval(refresh,15000);
</script></body></html>
