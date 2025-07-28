
<!-- Images used to open the lightbox -->
<!--<div class="row mt-5">
  <div class="column">
    <img src="assets/img/prato4.webp" onclick="openModal();currentSlide(1)" class="hover-shadow img-fluid">
  </div>
  <div class="column">
    <img src="assets/img/prato3.webp" onclick="openModal();currentSlide(2)" class="hover-shadow img-fluid">
  </div>
  <div class="column">
    <img src="assets/img/prato2.webp" onclick="openModal();currentSlide(3)" class="hover-shadow img-fluid">
  </div>
  <div class="column">
    <img src="assets/img/prato7.webp" onclick="openModal();currentSlide(4)" class="hover-shadow img-fluid">
  </div>
</div>-->

<!-- The Modal/Lightbox -->
<div id="myModal" class="modal">
  <span class="close cursor" onclick="closeModal()">&times;</span>
  <div class="modal-content">

    <div class="mySlides">
      <div class="numbertext">Lasanha a bolonhesa</div>
      <img src="assets/img/pratos/lasagna_a_bolognesa.png" style="width:100%">
    </div>

    <div class="mySlides">
      <div class="numbertext">Crespelle mistas</div>
      <img src="assets/img/pratos/crepelles_mistas.png" style="width:100%">
    </div>

    <div class="mySlides">
      <div class="numbertext">Berinjela a Parmegiana</div>
      <img src="assets/img/pratos/berinjela_a_parmegiana.jpg" style="width:100%">
    </div>

    <div class="mySlides">
      <div class="numbertext">Espetos mistos</div>
      <img src="assets/img/pratos/espetos_mistos.jpg" style="width:100%">
    </div>

    <div class="mySlides">
      <div class="numbertext">Almôndegas c/batatas</div>
      <img src="assets/img/pratos/almondegas_com_batatas.jpg" style="width:100%">
    </div>

    <div class="mySlides">
      <div class="numbertext">Lasanha de Frango</div>
      <img src="assets/img/pratos/lasanha_de_frango.png" style="width:100%">
    </div>
    
    <div class="mySlides">
      <div class="numbertext">Frango light c/vegetais</div>
      <img src="assets/img/pratos/frango_light.jpg" style="width:100%">
    </div>    

    <!-- Next/previous controls -->
    <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
    <a class="next" onclick="plusSlides(1)">&#10095;</a>

    <!-- Caption text -->
    <div class="caption-container">
      <p id="caption"></p>
    </div>

    <!-- Thumbnail image controls -->
    <div class="columns row">
      <div class="column col-2 my-2">
        <img class="demo img-fluid" src="assets/img/pratos/lasagna_a_bolognesa.png" onclick="currentSlide(1)" alt="Lasanha a bolonhesa - Folhas de massa artesanal em camadas alternadas ao ragú de carne com molho bechamel. 700g (serve 2 pessoas) - R$ 34,90">
      </div>

      <div class="column col-2 my-2">
        <img class="demo img-fluid" src="assets/img/pratos/crepelles_mistas.png" onclick="currentSlide(2)" alt="Crespelle mistas - Folhas de massa de Crespelle recheadas com presunto e queijo mussarela e outras com legumes e queijo mussarela, todo coberto com molho bechamel e queijo mussarela. 400g (serve 2 pessoas) - R$ 31,90">
      </div>

      <div class="column col-2 my-2">
        <img class="demo img-fluid" src="assets/img/pratos/berinjela_a_parmegiana.jpg" onclick="currentSlide(3)" alt="Berinjela a Parmegiana - Fatias finas de berinjela grelhadas em camada ao molho de tomates e queijo mussarela. 550g (serve 2 pessoas) - R$ 33,90">
      </div>

      <div class="column col-2 my-2">
        <img class="demo img-fluid" src="assets/img/pratos/espetos_mistos.jpg" onclick="currentSlide(4)" alt="Espetos mistos - Espetos de frango com linguiça toscana: cubos de coxas de frango desossado alternados a linguiça toscana e vegetais assados ao forno. 550g (serve 2 pessoas) - R$ 35,90">
      </div>

      <div class="column col-2 my-2">
        <img class="demo img-fluid" src="assets/img/pratos/almondegas_com_batatas.jpg" onclick="currentSlide(5)" alt="Almôndegas c/batatas - Almôndegas delicadas de carne magra e lombo de suíno, cobertas com molho de tomate, acompanhadas com batatas ao forno. 500g (serve 2 pessoas) - R$ 31,90">
      </div>
      
      <div class="column col-2 my-2">
        <img class="demo img-fluid" src="assets/img/pratos/lasanha_de_frango.png" onclick="currentSlide(6)" alt="Lasanha de Frango - Folhas de massa artesanal em camadas alternadas ao ragú de frango com molho bechamel. 700g (serve 2 pessoas) - R$ 34,90">
      </div>
      
      <div class="column col-2 my-2">
        <img class="demo img-fluid" src="assets/img/pratos/frango_light.jpg" onclick="currentSlide(7)" alt="Frango light c/vegetais - Frango desossado, marinado e assado ao forno, acompanhado de vegetais. 500g (serve 2 pessoas) - R$ 34,90">
      </div>      
    </div>
  </div>
</div>

<style>
  .row > .column {
  padding: 0 8px;
}

.row:after {
  content: "";
  display: table;
  clear: both;
}

/* Create four equal columns that floats next to eachother */
.column {
  float: left;
  width: 25%;
}

.columns{
  -webkit-box-orient: horizontal;
  background:#000;
}

/* The Modal (background) */
.modal {
  display: none;
  position: fixed;
  z-index: 1;
  padding-top: 100px;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: black;
}

/* Modal Content */
.modal-content {
  position: relative;
  background-color: #fefefe;
  margin: auto;
  padding: 0;
  width: 90%;
  max-width: 800px;
}

/* The Close Button */
.close {
  color: white;
  position: absolute;
  top: 10px;
  right: 25px;
  font-size: 35px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #999;
  text-decoration: none;
  cursor: pointer;
}

/* Hide the slides by default */
.mySlides {
  display: none;
}
.mySlides img{
  border-radius:0;
}
/* Next & previous buttons */
.prev,
.next {
  cursor: pointer;
  position: absolute;
  top: 50%;
  width: auto;
  padding: 16px;
  margin-top: -50px;
  color: white;
  font-weight: bold;
  font-size: 20px;
  transition: 0.6s ease;
  border-radius: 0 3px 3px 0;
  user-select: none;
  -webkit-user-select: none;
}

/* Position the "next button" to the right */
.next {
  right: 0;
  border-radius: 3px 0 0 3px;
}

/* On hover, add a black background color with a little bit see-through */
.prev:hover,
.next:hover {
  background-color: rgba(0, 0, 0, 0.8);
}

/* Number text (1/3 etc) */
.numbertext {
  color: #060606;
  font-size: 12px;
  padding: 8px 12px;
  position: absolute;
  top: 0;
}

/* Caption text */
.caption-container {
  text-align: center;
  background-color: black;
  padding: 2px 16px;
  color: white;
}

img.demo {
  opacity: 0.6;
  border-radius:0;
}

.active,
.demo:hover {
  opacity: 1;
}

img.hover-shadow {
  transition: 0.3s;
}

.hover-shadow:hover {
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
}
</style>

<script>
// Open the Modal
function openModal() {
  document.getElementById("myModal").style.display = "block";
}

// Close the Modal
function closeModal() {
  document.getElementById("myModal").style.display = "none";
}

var slideIndex = 1;
showSlides(slideIndex);

// Next/previous controls
function plusSlides(n) {
  showSlides(slideIndex += n);
}

// Thumbnail image controls
function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  var i;
  var slides = document.getElementsByClassName("mySlides");
  var dots = document.getElementsByClassName("demo");
  var captionText = document.getElementById("caption");
  if (n > slides.length) {slideIndex = 1}
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";
  dots[slideIndex-1].className += " active";
  captionText.innerHTML = dots[slideIndex-1].alt;
}
</script>