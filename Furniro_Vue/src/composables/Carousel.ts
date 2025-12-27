export function useCarousel(slideSelector: string, buttonSelector: string, dotSelector: string) {
  const currentSlide = ref(0)

  let slides: NodeListOf<Element>
  let nextBtns: NodeListOf<Element>
  let indicators: NodeListOf<Element>

  function updateSlides() {
    const right1 = (currentSlide.value + 1) % slides.length
    const right2 = (currentSlide.value + 2) % slides.length

    slides.forEach((slide, i) => {
      slide.classList.remove('center', 'right-1', 'right-2', 'hidden')

      if (i === currentSlide.value) {
        slide.classList.add('center')
      } else if (i === right1) {
        slide.classList.add('right-1')
      } else if (i === right2) {
        slide.classList.add('right-2')
      } else {
        slide.classList.add('hidden')
      }
    })

    updateIndicators()
  }

  function updateIndicators() {
    indicators.forEach((dot, i) => {
      dot.classList.toggle('active', i === currentSlide.value)
    })
  }

  function setupEvents() {
    nextBtns.forEach((btn) => {
      btn.addEventListener('click', () => {
        currentSlide.value = (currentSlide.value + 1) % slides.length
        updateSlides()
      })
    })

    indicators.forEach((dot) => {
      dot.addEventListener('click', () => {
        currentSlide.value = parseInt(dot.getAttribute('data-index') || '0', 10)
        updateSlides()
      })
    })
  }

  onMounted(() => {
    slides = document.querySelectorAll(slideSelector)
    nextBtns = document.querySelectorAll(buttonSelector)
    indicators = document.querySelectorAll(dotSelector)

    updateSlides()
    setupEvents()
  })

  return {
    currentSlide
  }
}
