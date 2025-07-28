<section class="section-foods">
  <div class="thrid-container">
    <div class="div-foods">
      <div class="first-column">
        <div class="div-foods-title">
          <div class="line-category foods"></div>
          <div>
            <h1 class="foods-title">Pratos frescos selecionados</h1>
            <p class="foods-desc">
              Praesent sed vehicula quam. Interdum et malesuada fames ac ante ipsum primis in<br>
              faucibus. Nunc convallis lorem at.
            </p>
          </div>
        </div>
        <div class="div-first-food">
          <div class="food-card">
            <div class="food-name">Frango light c/vegetais</div>
            <div class="food-desc">
              <img src="assets/img/icon1.webp" alt="">
              <span style="margin-right: 15px">Frango desossado, marinado e assado ao forno, acompanhado de vegetais. 500g (serve 2 pessoas) _*Atenção*: Produto resfriado, precisa terminar o preparo em forno ou microondas por alguns minutos._ </span>
              <img src="assets/img/icon2.webp" alt="">
              <span>R$ 34,90 </span>
            </div>
          </div>
          <img class="img-fluid cursor-pointer" src="assets/img/pratos/frango_light.jpg" alt="Frango light c/vegetais" srcset="" onclick="openModal();currentSlide(7);">
        </div>
        <div class="div-second-foods-img">
          <div class="div third-food">
            <div class="food-card">
              <div class="food-name second-ajust">Lasanha a bolonhesa</div>
              <div class="food-desc">
                <img src="assets/img/icon1.webp" alt="">
                <span style="margin-right: 15px">Folhas de massa artesanal em camadas alternadas ao ragú de carne com molho bechamel. 700g (serve 2 pessoas) -  _*Atenção:* Produto resfriado, precisa terminar o preparo em forno ou microondas por alguns minutos._ </span>
                <img src="assets/img/icon2.webp" alt="">
                <span>R$ 34,90</span>
              </div>
            </div>
            <img class="img-fluid cursor-pointer" src="assets/img/prato3.webp" alt="Lasanha a bolonhesa" srcset="" onclick="openModal();currentSlide(1);">
          </div>
          <div class="div fourth-food">
            <div class="food-card second-ajust">
              <div class="food-name">Lasanha de Frango</div>
              <div class="food-desc">
                <img src="assets/img/icon1.webp" alt="">
                <span style="margin-right: 15px">Lasanha de Frango - Folhas de massa artesanal em camadas alternadas ao ragú de frango com molho bechamel. 700g (serve 2 pessoas) - *Atenção*: Produto resfriado, precisa terminar o preparo em forno ou microondas por alguns minutos.</span>
                <img src="assets/img/icon2.webp" alt="">
                <span>R$ 34,90 </span>
              </div>
            </div>
            <img class="img-fluid cursor-pointer" src="assets/img/prato4.webp" alt="Lasanha de Frango" srcset="" onclick="openModal();currentSlide(6);">
          </div>
        </div>
        <div class="div-first-food">
          <div class="food-card">
            <div class="food-name">Espetos mistos</div>
            <div class="food-desc">
              <img src="assets/img/icon1.webp" alt="">
              <span style="margin-right: 15px">Espetos de frango com linguiça toscana: cubos de coxas de frango desossado alternados a linguiça toscana e vegetais assados ao forno. 550g (serve 2 pessoas) - _*Atenção:* Produto resfriado, precisa terminar o preparo em forno ou microondas por alguns minutos._</span>
              <img src="assets/img/icon2.webp" alt="">
              <span>R$ 35,90</span>
            </div>
          </div>
          <img class="img-fluid cursor-pointer" src="assets/img/prato7.webp" alt="Espetos mistos" srcset="" onclick="openModal();currentSlide(4);">
        </div>
      </div>
      <div class="second-column">
        <div class="div-second-food">
          <div class="food-card second-ajust">
            <div class="food-name">Crespelle mistas </div>
            <div class="food-desc">
              <img src="assets/img/icon1.webp" alt="">
              <span style="margin-right: 15px">Crespelle mistas - Folhas de massa de Crespelle recheadas com presunto e queijo mussarela e outras com legumes e queijo mussarela, todo coberto com molho bechamel e queijo mussarela. 400g (serve 2 pessoas) -  _*Atenção:* Produto resfriado, precisa terminar o preparo em forno ou microondas por alguns minutos._ </span>
              <img src="assets/img/icon2.webp" alt="">
              <span>R$ 31,90</span>
            </div>
          </div>
          <img class="img-fluid cursor-pointer" src="assets/img/prato2.webp" alt="Crespelle mistas" srcset="" onclick="openModal();currentSlide(2);">
        </div>
        <div class="div-fifth-food">
        <div class="food-card second-ajust thrid-ajust">
          <div class="food-name"> Almôndegas da casa </div>
            <div class="food-desc">
              <img src="assets/img/icon1.webp" alt="">
              <span style="margin-right: 15px"> Almôndegas c/batatas - Almôndegas delicadas de carne magra e lombo de suíno, cobertas com molho de tomate, acompanhadas com batatas ao forno. 500g (serve 2 pessoas) - _*Atenção:* Produto resfriado, precisa terminar o preparo em forno ou microondas por alguns minutos._</span>
              <img src="assets/img/icon2.webp" alt="">
              <span>R$ 31,90</span>
            </div>
          </div>
          <img class="img-fluid cursor-pointer" src="assets/img/prato6.webp" alt="Almôndegas da casa" srcset="" onclick="openModal();currentSlide(5);">
        </div>
        
      </div>
    </div>

    <?php include('partials/foods_modal.php') ?>

    <div class="div-foods-button">
      <a href="javascript:void(0);" onclick="openModal();currentSlide(1);" class="more-foods-btn">
        <span>Ver todos os pratos</span>
        <i class="fas fa-arrow-right"></i>
      </a>
    </div>
  </div>
</section>
