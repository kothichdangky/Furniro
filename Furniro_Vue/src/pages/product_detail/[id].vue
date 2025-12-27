<script setup>
import api from '@/api'
import { useRoute } from 'vue-router/auto'
import { useCartStore } from '@/composables/useCartStore'
const cart = useCartStore()
const route = useRoute('/product_detail/[id]')
const id = computed(() => route.params.id)

function addToCartFromDetail() {
  const selected = sizeData.value.find((s) => s.size === quantity_size.value)

  if (!selected || selected.quantity <= 0) {
    alert('⚠ Vui lòng chọn size còn hàng')
    return
  }
console.log('✅ Số lượng đang chọn:', selectedQuantity.value)
  const  productData = {
    id: id.value,
    product: product.value,
    size: quantity_size.value,
  quantity: selected.quantity, // 👈 đây là hàng tồn kho

    price: price.value,
    price_sale: price_sale.value,
    image:image.value,
    quantity: selected.quantity, // hàng trong kho
    cartQuantity: selectedQuantity.value, // số lượng muốn mua
  }

  cart.addProduct(productData)

}

const increaseQuantity = () => {
  const maxQty = sizeData.value.find((s) => s.size === quantity_size.value)?.quantity || 0
  if (selectedQuantity.value < maxQty) {
    selectedQuantity.value++
  }
}

const decreaseQuantity = () => {
  if (selectedQuantity.value > 1) {
    selectedQuantity.value--
  }
}


// lấy id để keep-alive
defineOptions({
  name: 'ProductDetail', // ⚠️ Rất quan trọng để keep-alive nhận diện
})

// Lắng nghe khi route thay đổi (VD: từ /product_detail/1 -> /product_detail/2)
onBeforeRouteUpdate((to) => {
  id.value = to.params.id
})

//end keep-alive
const selectedQuantity = ref(1)
const loaded = ref(false)
const quantity_size = ref(null)

const image = ref('')
const image_path = ref([])
const product = ref('')
const intro = ref('')
const price = ref('')
const price_sale = ref('')
const quantity = ref('')
const badge = ref('')

const sizeData = ref([]) // [{ size: 'M', quantity: 5 }]

const getProducts = async () => {
  try {
    const res = await api.get(`/api/product_detail/${id.value}`)
    console.log('✅ API trả về:', res.data)
    product.value = res.data.product
    price.value = res.data.price
    price_sale.value = res.data.price_sale
    quantity.value = res.data.quantity
    intro.value = res.data.intro
    badge.value = res.data.badge

    const sizes = res.data.sizes || []
    const quantities = res.data.size_quantities || []

    sizeData.value = sizes.map((size, index) => ({
      size,
      quantity: Number(quantities[index]) || 0,
    }))

    const preferredOrder = ['m', 'l', 'xl']
    const firstAvailable = preferredOrder.find((size) =>
      sizeData.value.some((item) => item.size === size && item.quantity > 0),
    )

    if (firstAvailable) {
      quantity_size.value = firstAvailable
    } else {
      const available = sizeData.value.find((item) => item.quantity > 0)
      quantity_size.value = available?.size || null
    }

    const imagePath = res.data.image
    image.value = imagePath.startsWith('http')
      ? imagePath
      : `http://localhost:8000/storage/${imagePath}`

    const addimageRaw = res.data.addimage
    if (Array.isArray(addimageRaw)) {
      image_path.value = addimageRaw.map((path) =>
        path.startsWith('http') ? path : `http://localhost:8000/storage/${path}`,
      )
    } else {
      image_path.value = []
    }
    await updateImageViewer()
    loaded.value = true
  } catch (error) {
    console.error('Lỗi khi lấy dữ liệu sản phẩm:', error)
  }
}



onMounted(() => {
  getProducts()
  const tab = document.querySelector('a[href="#home"]')
  if (tab) {
    const tabInstance = new bootstrap.Tab(tab)
    tabInstance.show() // Kích hoạt đúng tab
  }
  // ✅ Watch đúng cách khi ID thay đổi
})

watch(id, () => {
  getProducts()
})

const updateImageViewer = async () => {
  await nextTick()

  const main = document.getElementById('main-image')
  const thumbs = document.querySelectorAll('#thumbnail-list img')

  if (!main || thumbs.length === 0) return

  const changeImage = (src) => {
    main.style.opacity = 0
    setTimeout(() => {
      main.src = src
      main.style.opacity = 1
    }, 150)
  }

  thumbs.forEach((thumb) => {
    thumb.onclick = () => {
      changeImage(thumb.src)
      thumbs.forEach((t) => t.parentElement?.classList?.remove('active'))
      thumb.parentElement?.classList?.add('active')
    }
  })

  changeImage(thumbs[0].src)
  thumbs[0].parentElement?.classList?.add('active')
}

watch(id, (newId, oldId) => {
  if (newId !== oldId) {
    getProductData() // gọi lại API, hoặc reset state, setup ảnh lại...
  }
})



</script>

<style scoped>
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

.active-size {
  background-color: #b88e2f !important;
  color: white !important;
  border-color: #b88e2f !important;
  font-weight: bold;
  transform: scale(1.05);
  transition: all 0.2s ease-in-out;
}
</style>

<template>
  <section class="bg-warning-4">
    <BContainer class="py-5">
      <BRow>
        <BCol cols="12" lg="6" md="4" sm="4" class="d-flex">
          <div style="display: flex; align-items: center; gap: 20px">
            <p style="margin: 0; display: flex; align-items: center">
              <span class="text-secondary">Home</span>
              <b class="px-3">&gt;</b>
              <span class="text-secondary">Shop</span>
              <b class="ps-3">&gt;</b>
            </p>

            <div style="width: 2px; height: 40px; background: #9f9f9f; display: inline-block"></div>
            <p style="margin: 0; display: flex; align-items: center">{{ product }}</p>
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
                <img v-if="image" :src="image" style="max-width: 100%; border: 1px solid #ccc" />
                <div
                  v-for="(img, i) in image_path"
                  :key="i"
                  style="position: relative; display: inline-block"
                >
                  <img
                    :src="img"
                    alt="Ảnh phụ"
                    style="max-width: 100px; height: 120px; border: 1px solid #ccc"
                  />
                </div>
              </li>
            </ul>
          </BCol>

          <!-- Ảnh lớn -->
          <BCol lg="5" class="d-flex border-0 rounded-2" style="width: 423px; height: 510px">
            <img id="main-image" class="img-fluid rounded" />
          </BCol>

          <!-- Chi tiết sản phẩm -->
          <BCol lg="5" class="pe-0">
            <h1 class="product-title">{{ product }}</h1>

            <div class="rating d-flex align-items-center gap-2 mb-2">
              <h4 class="text-secondary">Rs. {{ price }}</h4>
            </div>

            <p class="product-description text-muted pe-5 mb-5">
              {{ intro }}
            </p>

            <h5 class="text-secondary">Sizes:</h5>

            <BButton
              v-for="item in sizeData"
              :key="item.size"
              class="bg-white text-dark mx-2 ms-0 my-2"
              style="width: 40px"
              :class="{
                'active-size': quantity_size === item.size,
              }"
              :disabled="item.quantity <= 0"
             @click="() => { quantity_size = item.size; selectedQuantity = 1 }">
              {{ item.size }}
            </BButton>
            <span class="d-flex text-secondary" v-if="quantity_size">
              <h5>
                Quantity of size:
                <strong class="text-dark">
                  {{ sizeData.find((s) => s.size === quantity_size)?.quantity ?? 0 }}
                </strong>
              </h5>
            </span>

            <div class="d-flex align-items-center">
              <h5 class="text-secondary">Quantity :</h5>
              <h5 class="text-dark px-1">{{ quantity }}</h5>
            </div>
            <div class="action d-flex my-5">
              <div class="input-group quantity-selector border rounded-2 me-3" style="width: 120px">
                <button
                  class="btn btn-outline-secondary border-0 py-2"
                  type="button"
                  @click="decreaseQuantity"
                >
                  −
                </button>
                <input
                  type="text"
                  class="form-control text-center border-0"
                  :value="selectedQuantity"
                  readonly
                />
                <button
                  class="btn btn-outline-secondary border-0 py-2"
                  type="button"
                  @click="increaseQuantity"
                >
                  +
                </button>
              </div>

              <BButton
                @click="addToCartFromDetail"
                class="btn border rounded-2 me-4 bg-warning-5 box2"
              >
                <h5>Add to cart</h5>
              </BButton>
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
                <a href=""
                  ><img class="pe-2" src="/src/images/product_detail/Vector.png" alt=""
                /></a>
                <a href=""
                  ><img class="pe-2" src="/src/images/product_detail/Vector2.png" alt=""
                /></a>
                <a href="">
                  <img class="pe-2" src="/src/images/product_detail/Vector3.png" alt=""
                /></a>
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

        <!-- Tab 3 -->
        <div id="menu2" class="container tab-pane">
          <br />

        <p class="mx-5 px-5">
            Embodying the raw, wayward spirit of rock ‘n’ roll, the Kilburn portable active stereo
            speaker takes the unmistakable look and sound of Marshall, unplugs the chords, and takes
            the show on the road.
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
      </div>
    </BContainer>
  </section>
</template>
