@extends('layouts.user')

@push('head')
  @vite('resources/css/about.css')
@endpush

@section('page')
  <section>

    <div class="about-us">
      <h2 class="section-title">
        ABOUT US
      </h2>
      <div class="section">
        <h3 class="section-header">OUR STORY</h3>
        <p class="section-description">
          Crown and Dial began with a simple passion for timeless craftsmanship and the belief that everyone deserves
          access
          to exceptional watches. What started as a small idea grew into a trusted online destination for pieces that
          balance elegance, precision, and lasting style. Every watch we offer reflects our appreciation for design that
          stands the test of time.
        </p>
      </div>

      <div class="section">
        <h3 class="section-header">OUR PROMISE TO YOU</h3>
        <p class="section-description">
          Your experience matters to us just as much as the watches we sell. From seamless shopping to attentive support,
          we
          strive to make every step feel effortless and enjoyable. We’re here to help you find a piece you’ll love wearing
          every day, and we’re committed to ensuring that Crown and Dial remains a trusted stop for your next treasured
          timepiece.
        </p>
      </div>

      <div class="section">
        <h3 class="section-header">OUR COLLECTION</h3>
        <p class="section-description">
          We curate our selection with care, choosing only watches that embody quality, durability, and thoughtful
          aesthetics. Whether you're a seasoned collector or discovering your first standout piece, our range is designed
          to
          suit every taste. From classic designs to contemporary trends, each watch is chosen to help you express your
          personal style with confidence.
        </p>
      </div>

      <div class="section">
        <h3 class="section-header">OUR COMMITMENT TO QUALITY</h3>
        <p class="section-description">
          At Crown and Dial, we collaborate with reputable manufacturers and designers who share our dedication to
          excellence. These partnerships allow us to offer premium craftsmanship at honest prices, ensuring that every
          customer receives a watch they can rely on for years to come. Quality isn’t just a feature—it’s the foundation
          of
          everything we do.
        </p>
      </div>

      <div class="section">
        <h3 class="section-header">WHERE ARE WE</h3>
        <iframe class="map"
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2422.263061519677!2d-1.8904664232387932!3d52.486798872039936!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4870bc8d9d5bbd39%3A0x86eefc99e36fcff8!2sAston%20University!5e0!3m2!1sen!2suk!4v1735862350000!5m2!1sen!2suk"
          width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
          referrerpolicy="no-referrer-when-downgrade">
        </iframe>
        <p>Aston University, Aston Triangle, Birmingham, B4 7ET, United Kingdom</p>
      </div>
    </div>
  </section>
@stop
