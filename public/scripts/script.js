
const app = Vue.createApp({
    data() {
      return {
        skuExists: false,

        formData: {
          sku: '',
          name: '',
          price: '',
          selectedOption: 'dvd',
          size: '',
          weight_kg: '',
          height: '',
          width: '',
          length: '',
        },
        errors: []
      }
    },
    computed: {
      hasSkuError() {
        return (
          this.errors.includes('SKU is required.')||
          this.errors.includes('This SKU already exists. Please provide a unique stock keeping unit (SKU).')
          );
      },
      hasNameError() {
        return this.errors.includes('Name is required.');
      },
      hasPriceError() {
        return (
          this.errors.includes('Price is required.') ||
          this.errors.includes('Price must be a number.')
        )
      },
      hasSizeError() {
        return (
          this.errors.includes('Size is required for DVDs.') ||
          this.errors.includes('Size must be a number.')

        )
      },
      hasWeightError() {
        return (
          this.errors.includes('Weight is required for books.')||
          this.errors.includes('Weight must be a number.')
        )
      },
      hasHeightError() {
        return (
          this.errors.includes('Height is required.')||
          this.errors.includes('Height must be a number.')
        )
      },
      hasWidthError() {
        return (
          this.errors.includes('Width is required.')||
          this.errors.includes('Width must be a number.')
        )
      },
      hasLengthError() {
        return (
          this.errors.includes('Length is required.')||
          this.errors.includes('Length must be a number.')
        )
      }
    },
    methods: {
      async checkSkuExists() {
        try{
        const response = await axios.get('/scandiweb/public/ProductValidate.php?sku=' + this.formData.sku)
          this.skuExists = response.data.exists;
          if(this.skuExists){
            this.errors.push('This SKU already exists. Please provide a unique stock keeping unit (SKU).');
          }
          return this.skuExists?false:true;
        }
        catch(error) {
          console.log(error);
          return false;
        };
        },
      validateForm() {
        this.errors = [];

        if (!this.formData.sku) {
          this.errors.push('SKU is required.');
        }

        if (!this.formData.name) {
          this.errors.push('Name is required.');
        }

        if (!this.formData.price) {
          this.errors.push('Price is required.');
        } else if (isNaN(this.formData.price)) {
          this.errors.push('Price must be a number.');
        }

        switch (this.formData.selectedOption) {
          case 'dvd':
            if (!this.formData.size) {
              this.errors.push('Size is required for DVDs.');
            } else if (isNaN(this.formData.size)) {
              this.errors.push('Size must be a number.');
            }
            break;
          case 'book':
            if (!this.formData.weight_kg) {
              this.errors.push('Weight is required for books.');
            } else if (isNaN(this.formData.weight_kg)) {
              this.errors.push('Weight must be a number.');
            }
            break;
          case 'furniture':
            if (!this.formData.height) {
              this.errors.push('Height is required.');
            } else if (isNaN(this.formData.height)) {
              this.errors.push('Height must be a number.');
            }
            if (!this.formData.width) {
              this.errors.push('Width is required.');
            } else if (isNaN(this.formData.width)) {
              this.errors.push('Width must be a number.');
            }
            if (!this.formData.length) {
              this.errors.push('Length is required.');
            } else if (isNaN(this.formData.length)) {
              this.errors.push('Length must be a number.');
            }
            break;
          default:
            break;
        }
        if (this.errors.length > 0) {
          return false;
        }

        return true;
      },
      async submitForm() {
        if (this.validateForm() && await this.checkSkuExists()) {
          const formData = new FormData();
          formData.append('sku', this.formData.sku.trim());
          formData.append('name', this.formData.name.trim());
          formData.append('price', this.formData.price.trim());
          formData.append('selectedOption', this.formData.selectedOption);
          formData.append('size', this.formData.size.trim());
          formData.append('weight_kg', this.formData.weight_kg.trim());
          formData.append('height', this.formData.height.trim());
          formData.append('width', this.formData.width.trim());
          formData.append('length', this.formData.length.trim());
          axios.post('/scandiweb/public/ProductHandler.php', formData)
            .then(response => {
              console.log(response.success);
              if (response.data.success) {
                // Redirect to index.php
                window.location.href = 'index.php';

              } else {
                // Display the error message
                console.log(response.data.error);
              }
            })
            .catch(error => {
              console.log(error);
          });

        }
      }
    }
  });

  app.mount('#app');