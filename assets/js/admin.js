(function(){
  const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
  const ordersTable = document.getElementById('ordersTable');
  const ordersBody = ordersTable?.querySelector('tbody');
  const ordersLoading = document.getElementById('ordersLoading');
  const messagesList = document.getElementById('messagesList');
  const messagesLoading = document.getElementById('messagesLoading');

  function r(url){ return fetch(url, { headers: { 'X-CSRF-Token': csrf }}).then(r=>r.json()); }

  function fmtMoney(v){ return 'R' + Number(v||0).toFixed(2); }
  function escapeHtml(s){ return String(s||'').replace(/[&<>"']/g, m=>({"&":"&amp;","<":"&lt;",">":"&gt;","\"":"&quot;","'":"&#39;"}[m])); }

  function loadOrders(){
    ordersLoading.style.display = '';
    ordersTable.style.display = 'none';
    r('php/admin-data.php?resource=orders').then(data=>{
      if(!data.success) throw new Error('Failed to load orders');
      ordersBody.innerHTML = '';
      (data.data||[]).forEach(o=>{
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td>${escapeHtml(o.order_id)}</td>
          <td>${escapeHtml(o.customer_name)}<div class="muted">${escapeHtml(o.customer_email||'')}</div></td>
          <td>${fmtMoney(o.total)}</td>
          <td>
            <select class="status-select" data-order-id="${escapeHtml(o.order_id)}">
              ${['pending','preparing','ready','completed'].map(s=>`<option value="${s}" ${String(o.status).toLowerCase()===s?'selected':''}>${s}</option>`).join('')}
            </select>
          </td>
          <td><span class="muted">${escapeHtml(o.created_at||'')}</span></td>
        `;
        ordersBody.appendChild(tr);
      });
      ordersBody.querySelectorAll('select.status-select').forEach(sel=>{
        sel.addEventListener('change', onChangeStatus);
      });
      ordersLoading.style.display = 'none';
      ordersTable.style.display = '';
    }).catch(()=>{
      ordersLoading.textContent = 'Failed to load orders';
    });
  }

  function onChangeStatus(e){
    const orderId = e.target.getAttribute('data-order-id');
    const status = e.target.value;
    fetch('php/update-order.php',{
      method:'POST',
      headers:{'Content-Type':'application/json','X-CSRF-Token': csrf},
      body: JSON.stringify({order_id: orderId, status})
    }).then(r=>r.json()).then(res=>{
      if(!res.success) throw new Error();
      loadStats();
    }).catch(()=>{
      alert('Failed to update status');
      loadOrders();
    });
  }

  function loadMessages(){
    messagesLoading.style.display = '';
    messagesList.style.display = 'none';
    r('php/admin-data.php?resource=messages').then(data=>{
      if(!data.success) throw new Error();
      const list = document.createElement('div');
      (data.data||[]).forEach(m=>{
        const item = document.createElement('div');
        item.style.borderBottom = '1px solid #eee';
        item.style.padding = '8px 0';
        item.innerHTML = `<div><strong>${escapeHtml(m.subject)}</strong></div>
                          <div class="muted">${escapeHtml(m.name)} • ${escapeHtml(m.email)}</div>
                          <div class="muted">${escapeHtml(m.created_at||'')}</div>`;
        list.appendChild(item);
      });
      messagesList.innerHTML = '';
      messagesList.appendChild(list);
      messagesLoading.style.display = 'none';
      messagesList.style.display = '';
    }).catch(()=>{
      messagesLoading.textContent = 'Failed to load messages';
    });
  }

  function loadStats(){
    r('php/admin-data.php?resource=stats').then(data=>{
      if(!data.success) return;
      document.getElementById('statOrders').textContent = data.data.orders;
      document.getElementById('statRevenue').textContent = fmtMoney(data.data.revenue);
      document.getElementById('statPending').textContent = data.data.pending;
      document.getElementById('statReady').textContent = data.data.ready;
    });
  }

  function init(){
    if(!ordersTable) return;
    loadOrders();
    loadMessages();
    loadStats();
    // refresh every 30s
    setInterval(loadOrders, 30000);
    setInterval(loadMessages, 60000);
    setInterval(loadStats, 15000);
  }

  document.addEventListener('DOMContentLoaded', init);
})();
