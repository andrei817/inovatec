let currentSlide = 0;
const slides = document.querySelectorAll('.carousel-slide');
let autoSlideInterval = null;  // Variável para armazenar o intervalo automático

function showSlide(index) {
  const totalSlides = slides.length;

  // Ajustar para loops infinitos
  if (index >= totalSlides) {
    currentSlide = 0;
  } else if (index < 0) {
    currentSlide = totalSlides - 1;
  } else {
    currentSlide = index;
  }

  // Mover o carrossel para o slide correto
  const offset = -currentSlide * 100;
  document.querySelector('.carousel-container').style.transform = `translateX(${offset}%)`;
}

function moveSlide(direction) {
  showSlide(currentSlide + direction);
  resetAutoSlide();  // Reinicia o carrossel automático após interação manual
}

// Função para iniciar o carrossel automático
function startAutoSlide() {
  autoSlideInterval = setInterval(() => {
    moveSlide(1);  // Move para o próximo slide automaticamente
  }, 5000);  // Intervalo de 3 segundos (ajustável)
}

// Função para parar o carrossel automático (opcional, caso queira pausar em interações)
function stopAutoSlide() {
  clearInterval(autoSlideInterval);
}

// Reiniciar o carrossel automático após a interação
function resetAutoSlide() {
  stopAutoSlide();  // Para o carrossel atual
  startAutoSlide(); // Reinicia o carrossel
}

// Exibe o primeiro slide ao carregar a página
showSlide(currentSlide);

// Inicia o carrossel automático
startAutoSlide();




  