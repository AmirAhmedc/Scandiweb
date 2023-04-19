
<!-- Have different page title for each page -->
<?php $page_title = 'Product Add';?>
<?php include '../private/shared/head.php';?>
<nav>
  <h1>Product Add</h1>
  <div id='form-buttons'>
    <button @click.prevent="submitForm" name='submit' id="submit" type="submit" class="btn" form='product_form' >Save</button>
    <a href="./index.php">
      <button type='button' class="btn btn-x" >Cancel</button>
    </a>
  </div>
</nav>
</header>
<hr>
<div class="container">
  <!-- Display errors using Vue.js -->
  <div v-show="errors.length" class="errors alert">
    <h3>Errors:</h3>
    <ul>
      <li v-for="(error, index) in errors" :key="index">{{ error }}</li>
    </ul>
  </div>

  <form action="" id='product_form' method='POST'>
    <div class="align-item" :class="{ 'has-error': hasSkuError }">
      <label for="sku">SKU</label>
      <input v-model.trim="formData.sku" type="text" name="sku" id='sku' class="form-control" placeholder="VKR12345">
    </div>

    <div class="align-item" :class="{ 'has-error': hasNameError }">
      <label for="name">Name</label>
      <input v-model.trim="formData.name" type="text" name='name' id='name' class="form-control" placeholder='Product name' >
    </div>

    <div class="align-item" :class="{ 'has-error': hasPriceError }">
      <label for="price">Price ($)</label>
      <input v-model.trim="formData.price" type="text" name='price' id='price' class="form-control" placeholder="0.0" >
    </div>

    <div class="align-item">
      <label for="productType">Type Switcher</label>
      <select name="typeSwitcher" id="productType" class="form-select" v-model="formData.selectedOption">
        <option value="dvd" id='DVD' >DVD</option>
        <option value="book" id='Book' >Book</option>
        <option value="furniture" id='Furniture'>Furniture</option>
      </select>
    </div>
    <div id='size-container' v-if="formData.selectedOption === 'dvd'">
      <div class="align-item" :class="{ 'has-error': hasSizeError }">
        <label for="size">Size (MB)</label>
        <input v-model.trim="formData.size" type="text" name='size' id='size' class="form-control" placeholder='0' maxlength='5' >
      </div>
      <div class="feedback">Please provide a size in megabyte (MB).</div>
    </div>
    <div id='weight-container' v-if="formData.selectedOption === 'book'">
      <div class="align-item" :class="{ 'has-error': hasWeightError }">
        <label for="weight">Weight (KG)</label>
        <input v-model.trim="formData.weight_kg" type="text" name='weight_kg' id='weight' class="form-control" placeholder='0.0' maxlength='3' >
      </div>
      <div class="feedback">Please provide a weight in kilograms (KG).</div>
    </div>
    <div id='dimensions-container' v-if="formData.selectedOption === 'furniture'">
      <div class="align-item" :class="{ 'has-error': hasHeightError }">
        <label for="height">Height (CM)</label>
        <input v-model.trim="formData.height" type="text" name='height' id='height' class="form-control" placeholder='0' maxlength='5' >
      </div>
      <div class="align-item" :class="{ 'has-error': hasWidthError }">
        <label for="width">Width (CM)</label>
        <input v-model.trim="formData.width" type="text" name='width' id='width' class="form-control" placeholder='0' maxlength='5' >
      </div>
      <div class="align-item" :class="{ 'has-error': hasLengthError }">
        <label for="length">Length (CM)</label>
        <input v-model.trim="formData.length" type="text" name='length' id='length' class="form-control" placeholder='0' maxlength='5' >
      </div>
      <div class="feedback">Please provide dimensions in HxWxL (height/width/length) format.</div>
    </div>
  </form>
  <?php include '../private/shared/footer.php';?>