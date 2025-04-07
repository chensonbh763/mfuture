// Configuração inicial do canvas
const canvas = document.getElementById("canvas");
const ctx = canvas ? canvas.getContext("2d") : null;
if (canvas) {
  canvas.width = window.innerWidth;
  canvas.height = window.innerHeight;
}

// Função para rastrear eventos de clique
function trackEvent(category, action, label, value) {
  gtag('event', action, {
    'event_category': category,
    'event_label': label,
    'value': value
  });
}

// Evento para clicar nos botões de oferta
document.querySelectorAll(".buyBtn").forEach((button) => {
  button.addEventListener("click", () => {
    const plan = button.getAttribute("data-plan");
    alert(`Parabéns! Você selecionou ${plan}.`);

    const price = parseFloat(
      button.closest('.price-box').querySelector('p').textContent.replace('Por apenas R$', '')
    );
    trackEvent('conversion', 'offer_click', plan, price);
  });
});

// Mensagem de boas-vindas
console.log("✨ Bem-vindo ao site de Coprodução Digital! Explore e aproveite. 🚀");

// Notifica a visualização da página no Google Analytics
trackEvent('page_interaction', 'page_view', 'Página Inicial Visitada');

// Rastreamento de vídeo (iframe)
const video = document.querySelector('iframe');
if (video) {
  video.addEventListener('load', () => {
    trackEvent('engagement', 'video_view', 'Vídeo Assistido');
  });
}

// Rastreamento de clique no botão de WhatsApp
const whatsappBtn = document.querySelector('.whatsapp-btn');
if (whatsappBtn) {
  whatsappBtn.addEventListener('click', () => {
    trackEvent('engagement', 'contact_click', 'Contato pelo WhatsApp');
  });
}

// Rastreamento de interação de checkout
document.querySelectorAll('a[target="_blank"]').forEach((link) => {
  link.addEventListener('click', () => {
    trackEvent('conversion', 'checkout_initiated', link.href, null);
  });
});
