<style>
.box {
  width: 30px;
  height: 30px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-wrap: nowrap;
}

.box2 {
  width: 215px;
  height: 64px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-wrap: nowrap;
}

.circle {
  width: 30px;
  height: 30px;
}

.bg-warning-4 {
  background-color: #f9f1e7;
}

.bg-warning-5 {
  background-color: #b88e2f;
}

a {
  text-decoration: none;
  color: black;
}
.thumb img {
  cursor: pointer;
  border: 2px solid transparent;
  border-radius: 5px;
  transition: border-color 0.2s;
  width: auto;
}

.thumb li {
  background-color: #f9f1e7;
  border: 2px solid transparent;
  border-radius: 5px;
}

.thumb .active img {
  border-color: #c0a156;
}

#main-image {
  transition: opacity 0.3s ease;
}

.nav-pills a {
  color: #9f9f9f;
  font-size: 24px;
}

.nav-pills a:hover {
  color: #000;
}

.nav-pills .nav-link {
  border-radius: 0rem;
  margin: 20px;
}

.nav-pills .nav-link.active,
.nav-pills .show > .nav-link {
  color: #000;
  background-color: white;
}
</style>
<script setup>
onMounted(() => {
  const thumbnails = document.querySelectorAll('#thumbnail-list img')
  const mainImage = document.getElementById('main-image')

  const tab = document.querySelector('a[href="#home"]')

  if (tab) {
    const tabInstance = new bootstrap.Tab(tab)
    tabInstance.show() // Kích hoạt đúng tab
  }

  function changeImage(src) {
    mainImage.style.opacity = 0
    setTimeout(() => {
      mainImage.src = src
      mainImage.style.opacity = 1
    }, 150)
  }

  thumbnails.forEach((thumb) => {
    thumb.addEventListener('click', () => {
      changeImage(thumb.src)

      thumbnails.forEach((t) => t.parentElement.classList.remove('active'))
      thumb.parentElement.classList.add('active')
    })
  })

  // ✅ Gán ảnh đầu tiên khi component vừa mount
  if (thumbnails[0]) {
    changeImage(thumbnails[0].src)
    thumbnails[0].parentElement.classList.add('active')
  }
})
</script>

<template>
  <section class="bg-warning-4">
    <BContainer class="py-5">
      <BRow>
        <BCol cols="12" lg="6" md="4" sm="4" class="d-flex">
          <div style="display: flex; align-items: center; gap: 20px">
            <p style="margin: 0; display: flex; align-items: center">
              <span class="text-secondary">Home</span>
              <b class="px-3">&gt;</b>
              <span class="text-secondary">Home</span>
              <b class="ps-3">&gt;</b>
            </p>

            <div style="width: 2px; height: 40px; background: #9f9f9f; display: inline-block"></div>
            <p style="margin: 0; display: flex; align-items: center">Asgaard sofa</p>
          </div>
        </BCol>
      </BRow>
    </BContainer>
  </section>

  <section>
    <BContainer class="py-5">
      <div class="p-4">
        <BRow>
          <!-- Thumbnails -->
          <BCol lg="1">
            <ul class="nav flex-column thumb" id="thumbnail-list">
              <li class="nav-item mb-2">
                <img src="/src/images/product_detail/product1.png" class="img-fluid" />
              </li>
              <li class="nav-item mb-2">
                <img src="/src/images/product_detail/product2.png" class="img-fluid" />
              </li>
              <li class="nav-item mb-2">
                <img src="/src/images/product_detail/product3.png" class="img-fluid" />
              </li>
              <li class="nav-item mb-2">
                <img src="/src/images/product_detail/product4.png" class="img-fluid" />
              </li>
            </ul>
          </BCol>

          <!-- Ảnh lớn -->
          <BCol
            lg="5"
            class="d-flex justify-content-center border-0 rounded-2"
            style="background-color: #f9f1e7; width: 423px; height: 510px"
          >
            <img id="main-image" class="img-fluid rounded" />
          </BCol>

          <!-- Chi tiết sản phẩm -->
          <BCol lg="5" class="pe-0">
            <h1 class="product-title">Asgaard sofa</h1>

            <div class="rating d-flex align-items-center gap-2 mb-2">
              <h4 class="text-secondary">Rs. 250,000.00</h4>
            </div>

            <p class="product-description text-muted pe-5 mb-5">
              Setting the bar as one of the loudest speakers in its class, the Kilburn is a compact,
              stout-hearted hero with a well-balanced audio which boasts a clear midrange and
              extended highs for a sound.
            </p>

            <h5 class="text-secondary">Sizes:</h5>
            <div class="my-3 d-flex">
              <span class="badge bg-warning-5 box me-2">M</span>
              <span class="badge bg-warning-4 box me-2">l</span>
              <span class="badge bg-warning-4 box me-2">XL</span>
            </div>

            <h5>Colors:</h5>
            <div>
              <span
                class="d-inline-block rounded-circle me-2 circle"
                style="background: #816dfa"
                title="Not in store"
              ></span>
              <span
                class="d-inline-block rounded-circle me-2 circle"
                style="background: black"
              ></span>
              <span class="d-inline-block rounded-circle circle" style="background: #b88e2f"></span>
            </div>

            <div class="action d-flex my-5">
              <div class="input-group quantity-selector border rounded-2 me-3" style="width: 120px">
                <button class="btn btn-outline-secondary border-0 py-2" type="button" id="decrease">
                  −
                </button>
                <input
                  type="text"
                  class="form-control text-center border-0"
                  id="quantity"
                  value="1"
                  readonly
                />
                <button class="btn btn-outline-secondary border-0 py-2" type="button" id="increase">
                  +
                </button>
              </div>
              <button class="btn border rounded-2 me-4 box2" type="button">Add To Cart</button>
              <button class="btn border rounded-2 box2" type="button">+ Compare</button>
            </div>
            <hr class="my-5" />
            <BRow>
              <BCol lg="2">
                <P>SKU</P>
                <P>Category</P>
                <P>Tags</P>
                <P>Share</P>
              </BCol>
              <BCol lg="1 p-0">
                <P>:</P>
                <P>:</P>
                <P>:</P>
                <P>:</P>
              </BCol>
              <BCol lg="4 p-0">
                <P>SS001</P>
                <P>Sofas</P>
                <P>Sofa, Chair, Home, Shop</P>
                <a href=""><img class="pe-2" src="/src/images/product_detail/Vector.png" alt="" /></a>
                <a href=""><img class="pe-2" src="/src/images/product_detail/Vector2.png" alt="" /></a>
                <a href=""> <img class="pe-2" src="/src/images/product_detail/Vector3.png" alt="" /></a>
              </BCol>
            </BRow>
          </BCol>
        </BRow>
      </div>
    </BContainer>
  </section>
  <hr class="mt-0 pt-0" />
  <section>
    <BContainer>
      <ul class="nav nav-pills justify-content-center mb-4" role="tablist">
        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="pill" href="#home">Description</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="pill" href="#menu1">Additional Information</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="pill" href="#menu2">Reviews[]</a>
        </li>
      </ul>

      <!-- Tab panes -->
      <div class="tab-content">
        <!-- Tab 1 -->
        <div id="home" class="container tab-pane" style="color: #9f9f9f">
          <p class="mx-5 px-5">
            Embodying the raw, wayward spirit of rock ‘n’ roll, the Kilburn portable active stereo
            speaker takes the unmistakable look and sound of Marshall, unplugs the chords, and takes
            the show on the road.
            <br />
            Weighing in under 7 pounds, the Kilburn is a lightweight piece of vintage styled
            engineering. Setting the bar as one of the loudest speakers in its class, the Kilburn is
            a compact, stout-hearted hero with a well-balanced audio which boasts a clear midrange
            and extended highs for a sound that is both articulate and pronounced. The analogue
            knobs allow you to fine tune the controls to your personal preferences while the
            guitar-influenced leather strap enables easy and stylish travel.
          </p>
          <BRow class="py-5 my-5">
            <BCol cols="12" lg="6" md="12" sm="12" class="d-flex">
              <div class="bg-warning-4">
                <img src="/src/images/product_detail/mini1.png" alt="" />
              </div>
            </BCol>
            <BCol cols="12" lg="6" md="12" sm="12" class="d-flex">
              <div class="bg-warning-4">
                <img src="/src/images/product_detail/mini2.png" alt="" />
              </div>
            </BCol>
          </BRow>
        </div>
        <!-- Tab 2 -->
        <div id="menu1" class="container tab-pane">
          <br />

          <div class="row">
            <div class="col-md-6">
              <img src="https://via.placeholder.com/600x400" class="d-block w-100 img-fluid" />
            </div>
            <div class="col-md-6">
              <h5>OLYMPUS BILLIARDS CLUB</h5>
              <p>Logo đầu tay thiết kế cho club Olympus, cách điệu theo phong cách trẻ trung...</p>
            </div>
          </div>
        </div>

        <!-- Tab 3 -->
        <div id="menu2" class="container tab-pane">
          <br />

          <div class="row">
            <div class="col-md-6">
              <img src="https://via.placeholder.com/600x400" class="d-block w-100 img-fluid" />
            </div>
            <div class="col-md-6">
              <h5>Hộp bài tây</h5>
              <p>
                Thiết kế hộp bài đơn giản, gợi nhớ đến slot machine và tông màu đỏ vàng mạnh mẽ...
              </p>
            </div>
          </div>
        </div>
      </div>
    </BContainer>
  </section>
</template>
