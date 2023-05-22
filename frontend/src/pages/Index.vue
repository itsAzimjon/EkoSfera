<template>
  <div class="dashboard">
    <div class="card-icon">
      <div class="card-icon-body">
        <div class="card-icon-i">
          <i class="fa fa-calendar"></i>
        </div>
        <h3>Calendar</h3>
      </div>
    </div>
    <div class="card-icon">
      <div class="card-icon-body">
        <div class="card-icon-i">
          <i class="fa fa-shopping-bag"></i>
        </div>
        <h3>Products</h3>
      </div>
    </div>
    <div class="card-icon">
      <div class="card-icon-body">
        <div class="card-icon-i">
          <i class="fa fa-cogs"></i>
        </div>
        <h3>Lorem ipsum dolor</h3>
      </div>
    </div>
    <div class="card-icon">
      <div class="card-icon-body">
        <div class="card-icon-i">
          <i class="fa fa-star"></i>
        </div>
        <h3>Lorem ipsum dolor</h3>
      </div>
    </div>
    <div class="card-icon">
      <div class="card-icon-body">
        <div class="card-icon-i">
          <i class="fa fa-star"></i>
        </div>
        <h3>Lorem ipsum dolor</h3>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Dashboard",
  data() {
    return {};
  },
};
</script>

<style>
@import url(https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css);
.dashboard {
  display: flex;
  flex-wrap: wrap;
}

.card-icon {
  flex: 1 1 18%;
  background: #ffffff;
  border-radius: 6px;
  border: 1px solid #deebfd;
  box-shadow: 0 3px 10px rgba(62, 85, 120, 0.045);
  margin: 0 8px 30px;
}

.card-icon .card-icon-body {
  padding: 20px;
}

.card-icon-i {
  width: 90px;
  height: 90px;
  border-radius: 50%;
  background-color: #e5f4e7;
  margin: 0 auto;
  position: relative;
}

.card-icon .card-icon-body h3 {
  text-align: center;
  margin-bottom: 0;
  margin-top: 10px;
  font-size: 18px;
  font-weight: 400;
}

.card-icon-i i {
  top: 50%;
  text-align: center;
  position: absolute;
  left: 50%;
  transform: translate(-50%, -50%);
  font-size: 45px;
  color: #1976d2;
}
</style>
