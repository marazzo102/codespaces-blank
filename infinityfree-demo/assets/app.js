const API_PRODUCTS = 'products.json';

function formatBRL(v){
  return v.toLocaleString('pt-BR',{style:'currency',currency:'BRL'});
}

async function loadProducts(){
  const res = await fetch(API_PRODUCTS);
  const products = await res.json();
  const container = document.getElementById('products');
  container.innerHTML = '';
  products.forEach(p=>{
    const card = document.createElement('article');
    card.className = 'product-card';
    card.innerHTML = `
      <h3>${p.title}</h3>
      <p>${p.description}</p>
      <div class="price">${formatBRL(p.price)}</div>
      <div>
        <button data-id="${p.id}" data-title="${escapeHtml(p.title)}" data-price="${p.price}">Adicionar</button>
      </div>`;
    container.appendChild(card);
  });
}

function escapeHtml(s){ return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

/* Carrinho simples em localStorage */
const CART_KEY = 'demo_cart_v1';
function getCart(){ return JSON.parse(localStorage.getItem(CART_KEY) || '[]'); }
function saveCart(c){ localStorage.setItem(CART_KEY, JSON.stringify(c)); renderCart(); }
function addToCart(item){ const c=getCart(); const idx=c.findIndex(x=>x.id===item.id); if(idx>-1){c[idx].quantity += item.quantity;} else c.push(item); saveCart(c); }
function removeFromCart(id){ const c=getCart().filter(x=>x.id!==id); saveCart(c); }

function renderCart(){
  const items = getCart();
  document.getElementById('cart-count').textContent = items.reduce((s,i)=>s+i.quantity,0);
  const container = document.getElementById('cart-items');
  container.innerHTML = '';
  let subtotal = 0;
  items.forEach(i=>{
    subtotal += i.unit_price * i.quantity;
    const div = document.createElement('div');
    div.className = 'cart-item';
    div.innerHTML = `<div>${i.title} x ${i.quantity}</div><div>${formatBRL(i.unit_price * i.quantity)} <button data-remove="${i.id}">✕</button></div>`;
    container.appendChild(div);
  });
  document.getElementById('cart-subtotal').textContent = formatBRL(subtotal);
}

document.addEventListener('click', (e)=>{
  if(e.target.matches('.product-card button')){
    const id = e.target.dataset.id; const title = e.target.dataset.title; const price = parseFloat(e.target.dataset.price);
    addToCart({id, title, unit_price: price, quantity: 1});
  }
  if(e.target.matches('#cart-toggle')){
    const panel = document.getElementById('cart');
    const hidden = panel.getAttribute('aria-hidden') === 'true';
    panel.setAttribute('aria-hidden', hidden ? 'false' : 'true');
  }
  if(e.target.matches('[data-remove]')){
    removeFromCart(e.target.getAttribute('data-remove'));
  }
  if(e.target.matches('#clear-cart')){
    saveCart([]);
  }
  if(e.target.matches('#checkout-btn')){
    startCheckout();
  }
});

async function startCheckout(){
  const items = getCart();
  if(!items.length){ alert('Seu carrinho está vazio'); return; }
  console.log('Iniciando checkout com itens:', items);
  // Monta payload compatível com Mercado Pago
  const payload = { items: items.map(i=>({ title: i.title, quantity: i.quantity, unit_price: i.unit_price })) };
  console.log('Payload:', payload);
  const res = await fetch('create_preference.php',{
    method: 'POST',headers: {'Content-Type':'application/json'},body: JSON.stringify(payload)
  });
  console.log('Resposta fetch:', res.status, res.ok);
  if(res.ok){
    // espera redirecionamento do PHP via Location
    const text = await res.text();
    console.log('Texto resposta:', text);
    // se o PHP retornar uma URL em JSON, usar: location.href = url
    try{
      const j = JSON.parse(text);
      if(j.init_point) location.href = j.init_point;
    }catch(e){
      // se PHP redirecionou, o browser já foi redirecionado; caso contrário mostra retorno
      console.log('Resposta do servidor:', text);
    }
  } else {
    alert('Erro ao iniciar checkout');
  }
}

window.addEventListener('load', async ()=>{ await loadProducts(); renderCart(); });
