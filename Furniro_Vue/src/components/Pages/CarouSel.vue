<style lang="css">
body {
  margin: 0;
  height: 1500px;
  font-family: sans-serif;
}

.carousel {
  width: 100%;
  max-width: 100vw;
  overflow: hidden;
  position: relative;
  height: 582px;
}

.carousel-track {
  position: relative;
  height: 100%;
}

.carousel-slide {
  position: absolute;
  top: 0;
  transition: all 0.5s ease;
  z-index: 0;
  opacity: 1;
  transform-origin: top left;
}

.carousel-slide img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
  border-radius: 10px;
}

.carousel-slide.center {
  width: auto;
  left: 0;
  transform: scale(1);
  z-index: 3;
}

.carousel-slide.right-1 {
  width: 38%;
  left: 48%;
  transform: scale(0.85);
  z-index: 2;
  transform-origin: top left;
}

.carousel-slide.right-2 {
  width: 38%;
  left: 97.5%;
  transform: translateX(-40%) scale(0.85);
  z-index: 1;
  transform-origin: top left;
}

.carousel-slide.hidden {
  opacity: 0;
  transform: scale(0);
  pointer-events: none;
}

.carousel-slide:not(.center) .info-wrapper {
  display: none;
}

.info-wrapper {
  position: absolute;
  bottom: 20px;
  left: 20px;
  display: flex;
  align-items: stretch;
}

.slide-info {
  background: white;
  padding: 20px 65px;
  padding-left: 25px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: 250px;
  opacity: 70%;
}

.next-btn {
  background: #c0a156;
  border: none;
  color: white;
  height: 48px;
  width: 48px;
  padding: 6px 10px;
  font-size: 16px;
  cursor: pointer;
  line-height: 1;
  align-self: flex-end;
  opacity: 100%;
}

/* ✅ THÊM phần này */
.carousel-indicators {
  position: absolute;
  bottom: 20px;
  left: 52%;
  margin: 0 0;
  transform: translateX(-50%);
  display: flex;
  gap: 8px;
  z-index: 10;
}

.carousel-indicators .dot {
  width: 12px;
  height: 12px;
  background-color: #ccc;
  border-radius: 50%;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.carousel-indicators .dot.active {
  background-color: #c0a156;
}
</style>

<script lang="ts" setup>
import { useCarousel } from '@/composables/Carousel'

useCarousel('.carousel-slide', '.next-btn', '.dot')
</script>

<template>
  <div class="carousel">
    <div class="carousel-track">
      <div class="carousel-slide">
        <img class="border-0" src="/src/images/home/Carousel/Inner_Peace.png" alt="Slide 1" />
        <div class="info-wrapper">
          <div class="slide-info">
            <p>01 — Bed Room</p>
            <h2>Inner Peace</h2>
          </div>
          <button class="next-btn">→</button>
        </div>
      </div>
      <div class="carousel-slide">
        <img src="/src/images/home/Carousel/Dinner.png" alt="Slide 2" />
        <div class="info-wrapper">
          <div class="slide-info">
            <p>02 — Dining Room</p>
            <h2>Light Meal</h2>
          </div>
          <button class="next-btn">→</button>
        </div>
      </div>
      <div class="carousel-slide">
        <img src="/src/images/home/Carousel/Table.png" alt="Slide 3" />
        <div class="info-wrapper">
          <div class="slide-info">
            <p>03 — Kitchen</p>
            <h2>Modern Touch</h2>
          </div>
          <button class="next-btn">→</button>
        </div>
      </div>
    </div>
    <div class="carousel-indicators">
      <span class="dot" data-index="0"></span>
      <span class="dot" data-index="1"></span>
      <span class="dot" data-index="2"></span>
    </div>
  </div>
</template>
