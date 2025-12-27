<script setup>
import { useRoute } from 'vue-router'

const route = useRoute()
</script>

<style lang="css " scoped>

h1 {
  font-weight: 500;
  font-size: 52px;
  position: relative;
}

h2 {
  font-weight: 500;
  font-size: 32px;
  position: relative;
}

h3 {
  font-weight: 500;
  font-size: 24px;
  position: relative;
}

.h1_5{
  font-weight: 500;
  font-size: 40px;
  position: relative;
}

.poster1 {
  background-image: url("/src/images/home/poster.png");
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  min-height: 600px; /* hoặc vh: 100vh nếu muốn full màn hình */
  width: 100%;

}

.nowrap {
    flex-wrap: nowrap;
  }

.bg-warning-2 {
  background-color:#B88E2F
}

.bg-warning-3 {
  background-color:#FCF8F3
}

.text-warning-2 {
  color:#B88E2F
}

.pb-120{
  padding-bottom:256px;
}

.yellow {
  background-color: #FFF3E3;
}

.position-square {
  max-width: 90%;
  height: auto;
  padding: 2rem;
  margin: auto;
}

.p-200{
  padding-top:240px;
  padding-left: 120px
}

@media (min-width: 992px) {
  .position-square {
    width: 743px;
    height: 443px;
    padding: 144px;
    margin-left: 468px;
  }

}
@media (max-width:960px){
    .nowrap {
        flex-wrap: wrap;
        text-align: center;
        align-items: stretch;
    } }
</style>

<template>
  <section class="poster1 d-flex align-items-center">
    <BContainer class="pb-120">
      <div class="position-square pe-5">
        <div class="yellow pt-5 ps-5 pe-5 pb-4">
          <b>New Arrival</b>
          <h1 class="text-warning-2">Discover Our <br />New Collection</h1>
          <p>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec
            ullamcorper mattis.
          </p>

          <BButton to="/detail_product" class="border-0 rounded-0 bg-warning-2 ps-5 pe-5 pt-4 pb-3 mt-2"
              ><h5>BUY NOW</h5></BButton
            >
        </div>
      </div>
    </BContainer>
  </section>
  <section>
    <BContainer>
      <div class="text-center m-5">
        <h2>Browse The Range</h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
      </div>
      <BRow class="justify-content-between text-center">
        <BCol cols="12" lg="4" md="12" sm="12" class="">
          <img src="/src/images/home/Dining.png" alt="" />
          <h3 class="p-3">Dining</h3>
        </BCol>
        <BCol cols="12" lg="4" md="12" sm="12" class="">
          <img src="/src/images/home/Living.png" alt="" />
          <h3 class="p-3">Living</h3>
        </BCol>
        <BCol cols="12" lg="4" md="12" sm="12" class="">
          <img src="/src/images/home/Bedroom.png" alt="" />
          <h3 class="p-3">Bedroom</h3>
        </BCol>
      </BRow>
    </BContainer>
  </section>
  <section>
    <BContainer>
      <div class="text-center m-5">
        <h2>Our Products</h2>
      </div>
      <Product />
    </BContainer>
  </section>

  <section>

    <div class="bg-warning-3" style="margin-top: 100px">
      <BRow class="nowrap">
        <BCol cols="12" lg="5" md="12" sm="12" class="p-200">
          <div class="align-content-center h1_5">
            <b>50+ Beautiful rooms inspiration</b>
            <p style="font-size: 16px">
              Our designer already made a lot of beautiful prototipe of rooms that inspire you
            </p>
            <BButton class="border-0 rounded-0 bg-warning-2 ps-5 pe-5 pt-4 pb-3 mt-2"
              ><h5>Explore More</h5></BButton
            >
          </div>
        </BCol>
        <BCol cols="12" lg="7" md="12" sm="12" class="">
          <div class="align-content-center mt-5">
            <CarouSel />
          </div>
        </BCol>
      </BRow>
    </div>
  </section>

  <section class="mb-5">
    <BContainer>
      <div class="m-5 text-center ">
        <b style="font-size: 20px; color: #616161;">Share your setup with</b>
        <h1 class="h1_5">#FuniroFurniture</h1>
      </div>
    </BContainer>
    <div>
      <!-- <img class="w-100 img-fluid" src="/src/images/home/Funiro.PNG" alt=""> -->
       <img class="w-100 img-fluid" src="/src/images/home/Furinoo.png" alt="" draggable="false">
    </div>
  </section>
</template>
