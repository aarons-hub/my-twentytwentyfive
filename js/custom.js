document.addEventListener("DOMContentLoaded", function () {
  // Get the <ul> element with class "custom"
  var ulElement = document.querySelector("ul.customtiles");

  // Check if the <ul> element exists and contains <li> tags
  if (ulElement) {
    var liCount = ulElement.getElementsByTagName("li").length;

    // Add the appropriate class based on the number of <li> tags
    if (liCount > 2) {
      ulElement.classList.add("columns-3");
    } else {
      ulElement.classList.add("columns-2");
    }
  }
});

jQuery(function ($) {
  // Front page carousel
  $("#front-page-carousel").owlCarousel({
    // animateOut: "fadeOut",
    autoplayTimeout: 7000,
    autoplaySpeed: 2000,
    autoplayHoverPause: true,
    autoplay: true,
    loop: true,
    dots: true,
    margin: 0,
    responsive: { 0: { items: 1 }, 600: { items: 1 }, 1000: { items: 1 } },
  });

  // Front page carousel
  $("#custom-sliderxx").owlCarousel({
    // animateOut: "fadeOut",
    autoplayTimeout: 7000,
    autoplaySpeed: 2000,
    autoplayHoverPause: true,
    autoplay: true,
    loop: true,
    dots: true,
    margin: 0,
    responsive: { 0: { items: 1 }, 600: { items: 1 }, 1000: { items: 1 } },
  });

  // Front page logo carousel
  $("#front-page-logos").owlCarousel({
    autoplayTimeout: 7000,
    autoplaySpeed: 2000,
    autoplayHoverPause: true,
    autoplay: true,
    loop: true,
    dots: true,
    nav: false,
    margin: 0,
    responsive: { 0: { items: 2 }, 600: { items: 4 }, 1000: { items: 4 } },
  });

  // Maybe useful for api stuff
  // const parentPageId = param.parent_page_id;
  // const siteurl = param.siteurl;
  // const currentPageId = param.current_page_id;

  // Animate footer anchor
  let offset = 900;
  let duration = 700;
  let anchor = "#custom-top-btn .wp-block-button";

  $(window).scroll(function () {
    if ($(this).scrollTop() > offset) {
      $(anchor).addClass("visible");
    } else {
      $(anchor).removeClass("visible");
    }
    return false;
  });

  $(anchor + " a").click(function (event) {
    event.preventDefault();
    $("html, body").animate({ scrollTop: 0 }, duration);
    return false;
  });
});

jQuery(function ($) {
  $(".accordion .heading").click(function (e) {
    e.preventDefault();

    let $this = $(this);
    let $content = $this.next(".content");

    $(".accordion .heading").not($this).removeClass("show");
    $(".accordion .content").not($content).slideUp();

    $this.toggleClass("show");
    $content.slideToggle();
  });
});

jQuery(function ($) {
  $("table").basictable({
    breakpoint: 599,
  });
});

jQuery(function ($) {
  $("ul.has-children > li").append(
    '<button class="toggle-button">&nbsp;</button>'
  );
  $(".toggle-button").on("click", function () {
    $(this).toggleClass("active");
    $(this).siblings("ul.child").toggleClass("show");
  });
});

jQuery(function ($) {
  $(".quicklaunch li.page_item_has_children").append(
    '<button class="toggle-button">&nbsp;</button>'
  );
  $(".toggle-button").on("click", function () {
    $(this).toggleClass("active");
    $(this).siblings("ul.children").toggleClass("show");
  });
});
