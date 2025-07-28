<section class="clients">
  <h1 class="clients-title">O que nossos clientes dizem</h1>
  <p class="clients-desc">
    Praesent sed vehicula quam. Interdum et malesuada fames ac ante ipsum primis in faucibus. Nunc convallis<br />
    lorem at diam finibus eleifend. Duis molestie facilisis augue, vel ullamcorper metus mollis sed.
  </p>
  <div class="div-client-bg-red"></div>
  <div class="div-clients-cards mt-5">
    <div class="glide glide-clients">
      <div class="glide__track" data-glide-el="track">
        <div class="glide__slides">
          <div class="glide__slide">
            <div class="card-client">
              <img src="assets/img/clientphoto.webp" alt="Joao">
              <p class="card-client-desc">
                Praesent sed vehicula quam. Interdum et malesuada fames ac ante ipsum primis in.
              </p>
              <h5 class="card-client-name">
                João da Silva, comprou Lasanha Bolonhesa
              </h5>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    new Glide('.glide-clients', {
      type: 'carousel',
      startAt: 0,
      perView: 2,
      gap: 15,
      peek: {
        before: 160,
        after: 160
      },
      breakpoints: {
        700: {
          perView: 1,
          peek: {
            before: 10,
            after: 10
          },
        }
      }
    }).mount();
  })
</script>