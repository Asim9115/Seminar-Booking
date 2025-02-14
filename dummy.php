<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Image Slider</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>

  <div class="slider">
    <div class="slides">
      <!-- Each div with the class 'slide' contains one image -->
      <div class="slide"><img src="seminar1.jpg" alt="Seminar 1"></div>
      <div class="slide"><img src="seminar2.jpg" alt="Seminar 2"></div>
      <div class="slide"><img src="seminar1.jpg" alt="Seminar 1"></div>
      <div class="slide"><img src="seminar2.jpg" alt="Seminar 2"></div>
    </div>
  </div>

</body>
</html>

<style>* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: Arial, sans-serif;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  background-color: #f3f3f3;
}

.slider {
  width: 500px; /* Adjusts dynamically based on image size */
  height: 300px;
  overflow: hidden;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.slides {
  display: flex;
  animation: slide 20s infinite;
}

.slide {
  flex-shrink: 0;
  width: 100%; /* Each slide takes the full width of the slider */
}

.slide img {
  display: block;
  max-width: 100%; /* Ensure image fits within the slide */
  height: auto; /* Maintain aspect ratio of images */
}

@keyframes slide {
  0% {
    transform: translateX(0%);
  }
  25% {
    transform: translateX(-100%);
  }
  50% {
    transform: translateX(-200%);
  }
  75% {
    transform: translateX(-300%);
  }
  100% {
    transform: translateX(0%);
  }
}


</style>