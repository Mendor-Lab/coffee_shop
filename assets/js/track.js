(function(){
  const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
  const form = document.getElementById('trackForm');
  const input = document.getElementById('orderIdInput');
  const result = document.getElementById('result');
  const orderSummary = document.getElementById('orderSummary');
  const progressInner = document.getElementById('progressInner');
  const etaEl = document.getElementById('eta');
  const msgEl = document.getElementById('trackMsg');

  let pollTimer = null;
  let countdownTimer = null;
  let currentOrderId = '';

  function escapeHtml(s){ return String(s||'').replace(/[&<>"]|'/g, m=>({"&":"&amp;","<":"&lt;",">":"&gt;","\"":"&quot;","'":"&#39;"}[m])); }

  function statusPill(status){
    const s = String(status||'pending').toLowerCase();
    return `<span class="status-pill status-${s}">${s}</span>`;
  }

  function fmtMoney(v){ return 'R' + Number(v||0).toFixed(2); }

  function formatEta(seconds){
    seconds = Math.max(0, Math.floor(seconds||0));
    const m = Math.floor(seconds/60);
    const s = seconds % 60;
    return `${m}m ${s}s`;
  }

  function startCountdown(seconds){
    if (countdownTimer) clearInterval(countdownTimer);
    let remain = Math.max(0, Math.floor(seconds||0));
    function tick(){
      etaEl.textContent = remain > 0 ? `Estimated time remaining: ${formatEta(remain)}` : 'Almost ready!';
      if (remain <= 0) return; 
      remain -= 1;
    }
    tick();
    countdownTimer = setInterval(tick, 1000);
  }

  function updateUI(payload){
    const o = payload.data;
    const progress = Math.max(0, Math.min(100, payload.progress||0));
    orderSummary.innerHTML = `<div><strong>Order:</strong> ${escapeHtml(o.order_id)} ${statusPill(o.status)}</div>
                              <div class="muted">${escapeHtml(o.customer_name)} • ${escapeHtml(o.customer_email||'')}</div>
                              <div><strong>Total:</strong> ${fmtMoney(o.total)}</div>
                              <div class="muted">Placed: ${escapeHtml(o.created_at||'')}</div>`;
    progressInner.style.width = progress + '%';
    startCountdown(payload.eta_seconds || 0);
    result.style.display = '';
  }

  function fetchOrder(orderId){
    msgEl.textContent = 'Fetching order status…';
    return fetch(`php/get-order.php?order_id=${encodeURIComponent(orderId)}`, {
      headers: { 'X-CSRF-Token': csrf }
    }).then(r=>r.json()).then(data=>{
      if (!data.success) throw new Error(data.message || 'Not found');
      updateUI(data);
      msgEl.textContent = '';
      return data;
    }).catch(err=>{
      msgEl.textContent = err.message || 'Order not found';
      result.style.display = 'none';
      throw err;
    });
  }

  function startPolling(){
    if (pollTimer) clearInterval(pollTimer);
    pollTimer = setInterval(()=>{
      if (!currentOrderId) return;
      fetchOrder(currentOrderId).then(data=>{
        // Stop polling when completed
        const st = String(data.data?.status||'').toLowerCase();
        if (st === 'completed') {
          clearInterval(pollTimer);
          pollTimer = null;
        }
      }).catch(()=>{});
    }, 15000);
  }

  function onSubmit(e){
    e.preventDefault();
    const orderId = input.value.trim();
    if (!orderId) return;
    currentOrderId = orderId;
    fetchOrder(orderId).then(()=>{
      startPolling();
    }).catch(()=>{
      if (pollTimer) { clearInterval(pollTimer); pollTimer = null; }
    });
  }

  function init(){
    if (!form) return;
    form.addEventListener('submit', onSubmit);
    // Autofill if URL has ?order_id=
    const params = new URLSearchParams(location.search);
    const oid = params.get('order_id');
    if (oid) {
      input.value = oid;
      form.dispatchEvent(new Event('submit'));
    }
  }

  document.addEventListener('DOMContentLoaded', init);
})();
