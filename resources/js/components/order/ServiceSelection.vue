<template>
  <div>
    <h5 class="card-title sky">
      Step <b>1</b>/<span class="small">3</span> TYPE OF WORK AND DEADLINE
    </h5>
    <hr />
    <div class="form-group">
      <label class="sky">Service Type</label>
      <v-select
        label="Select"
        v-model="form.service_model"
        :items="servicesObject"
        item-title="title"
        item-value="value"
        @update:model-value="getAdditionalServices"
      ></v-select>
    </div>
    <div class="form-group">
      <label class="sky">Writers</label>
      <v-select
        label="Select"
        v-model="form.writer_model"
        :items="writersObject"
        item-title="title"
        item-value="value"
      ></v-select>
    </div>
    <div class="form-group">
      <label class="sky">Work Level</label>
      <div>
        <div class="btn-group btn-group-toggle flex-wrap" data-toggle="buttons">
          <label
            class="btn btn-outline-primary"

            @click="workLevelChanged(row.id, index)"
            :class="{ 'active': form.work_level_id === Number(row.id) }"
            v-for="(row, index) in levels"
            :key="index"
          >
            <input
              type="radio"
              class="btn-group-toggle"
              :id="'workLevel_' + index"
              :value="row.id"
              autocomplete="off"
              v-model="form.work_level_id"
            />
            {{ row.name }}
          </label>
        </div>
      </div>
    </div>
    <div class="form-row" v-if="form.service_model.price_type_id === pricingTypes.perPage">
      <div class="form-group col-md-4">
        <label class="sky">Number of pages</label>
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <button
              type="button"
              class="btn custom-btn-outline-blue"
              v-on:click="changePageNumber(-1)"
            >-</button>
          </div>
          <input
            type="text"
            class="form-control text-center custom-btn-outline-blue"
            aria-describedby="basic-addon1"
            v-model="form.number_of_pages"
            v-on:keypress="isNumber($event)"
            @change="validateNumberOfPages"
          />
          <div class="input-group-append">
            <div class="input-group-prepend">
              <button
                type="button"
                class="btn custom-btn-outline-blue"
                v-on:click="changePageNumber(1)"
              >+</button>
            </div>
          </div>
        </div>
        <div class="invalid-feedback d-block" v-if="errors.number_of_pages">{{ errors.number_of_pages[0] }}</div>
      </div>
      <div class="form-group col-md-8">
        <label class="sky">
          Spacing
          <span
            data-toggle="tooltip"
            title="Single-spaced - The final paper will have one line spacing between lines."
          >
            <i class="fas fa-question-circle"></i>
          </span>
        </label>
        <div>
          <div class="btn-group btn-group-toggle" data-toggle="buttons">
            <label
              v-for="row in spacings"
              class="btn btn-outline-primary"
              v-on:click="spacingTypeChanged(row.id)"
              :class="form.spacing_type == row.id ? 'active': ''"
              :key="row.id"
            >
              <input
                type="radio"
                class="btn-group-toggle"
                :id="'spacing_' + row.id"
                :value="row.id"
                autocomplete="off"
                v-model="form.spacing_type"
              />
              {{ row.name }}
            </label>
          </div>
        </div>
      </div>
    </div>
    <div class="form-row">
      <div
        class="form-group col-md-6"
        v-if="form.service_model.price_type_id == pricingTypes.perWord"
      >
        <label class="sky">Number of Words</label>
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <button
              type="button"
              class="btn btn-outline-secondary"
              v-on:click="changeNumberOfWords(-20)"
            >-</button>
          </div>
          <input
            type="text"
            class="form-control text-center"
            aria-describedby="basic-addon1"
            v-model="form.number_of_words"
            v-on:keypress="isNumber($event)"
            @change="validateNumberOfWords"
          />
          <div class="input-group-append">
            <div class="input-group-prepend">
              <button
                type="button"
                class="btn btn-outline-secondary"
                v-on:click="changeNumberOfWords(20)"
              >+</button>
            </div>
          </div>
        </div>
        <div class="invalid-feedback d-block" v-if="errors.number_of_words">{{ errors.number_of_words[0] }}</div>
      </div>

      <div
        class="form-group"
        v-bind:class="{ 'col-md-6': (form.service_model.price_type_id == pricingTypes.perWord), 'col-md-12': (form.service_model.price_type_id != pricingTypes.perWord) }"
      >
        <label class="sky">Urgency</label>
        <v-select
          label="Select"
          v-model="form.urgency_model"
          :items="urgenciesObject"
          item-title="title"
          item-value="value"
        ></v-select>
      </div>
    </div>
    <div v-if="additional_services.length > 0">
      <h5 class="sky font-weight-bold">Additional Services</h5>
      <div class="card mb-3" v-for="row in additional_services" :key="row.id">
        <div class="row no-gutters">
          <div class="col-md-8">
            <div class="card-body">
              <h5 class="card-title sky">{{ row.name }}</h5>
              <p class="card-text">{{ row.description }}</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="d-flex justify-content-center" style="margin-top: 40px;">
              <a href="#" v-on:click.prevent="additionalServiceChanged(row.id, row)">
                <div class="btn btn-block bg-sky text-white btn-outline-primary " v-bind:class="getServiceContainerClass(row.id)">
                  <span v-if="addedServiceList(row.id)">
                    <i class="fas fa-check-circle"></i>Added
                  </span>
                  <span class="bg-sky text-white" v-else>
                    <i class="fas fa-plus"></i> Add
                  </span>
                  {{ formatMoney(row.rate)   }}
                </div>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div v-if="user_id">
      <button
        :disabled="hasError"
        type="button"
        class="btn bg-sky  text-white btn-lg btn-block"
        @click.prevent="changeTab(2)"
        >
        <i class="fas fa-arrow-circle-right text-white "></i> Next
       </button>

     <!-- <v-btn

   variant="outlined"
   class="btn btn-lg btn-block"
   color="#229ad8"
   @click.prevent="changeTab(2)"
    >
   <i class="fas fa-arrow-circle-right"></i> Next
   </v-btn> -->

     </div>
     <div v-else>
      <button
        type="button"
        class="btn btn-success btn-lg btn-block"
        @click.prevent="changeTab(2)"
      >
        <i class="fas fa-sign-in-alt"></i> Sign in to place your order
      </button>
      <a :href="create_account_url" class="btn btn btn-info btn-lg btn-block">
        <i class="fas fa-user-plus"></i> Create account
      </a>
    </div>
  </div>
</template>

<script>

import Multiselect from "vue-multiselect";
import VueMultiselect from "vue-multiselect";
import { ref, reactive,watch,Vue,mounted, onMounted } from 'vue';

export default {
  components: {
    Multiselect,
    VueMultiselect
  },
  props: {
    pricingTypes: {
      default: {},
    },
    services: {
      default: {},
    },
    levels: {
      type: Array,
      default() {
        return {};
      },
    },
    urgencies: {
      type: Array,
      default() {
        return {};
      },
    },
    spacings: {
      type: Array,
      default() {
        return {};
      },
    },
    user_id: {
      type: [Boolean, Number],
      default() {
        return null;
      },
    },
    restricted_order_page_url: {
      type: String,
      default() {
        return null;
      },
    },
    additional_services_by_service_id_url: {
      type: String,
      default() {
        return null;
      },
    },
    create_account_url: {
      type: String,
      default() {
        return null;
      },
    },
    writer_list: {
      default: {},
    },
  },



  setup(props, { emit }) {
    const hasError = ref(false);
    const errors = ref({});
    const additional_services = ref([]);
    const serviceOptions = ref(['list', 'of', 'options']);
    const form = reactive({
      service_model: props.services ? props.services[0] : {},
      writer_model: props.writer_list ? props.writer_list[0] : {},
      urgency_model: props.urgencies ? props.urgencies[0] : {},
      work_level_model: props.levels ? props.levels[0] : {},
      work_level_id: props.levels ? props.levels[0].id : 1,
      number_of_words: props.services[0].minimum_order_quantity,
      number_of_pages: props.services[0].minimum_order_quantity,
      spacing_type: "double",
      added_services: [],
    });

    const checkError = () => {
      const errorList = { ...errors.value };
      hasError.value = Object.keys(errorList).length > 0;
    };

    const triggerChange = () => {
      emit("dataChanged", form);
    };

    onMounted(() => {
      triggerChange(); // Call your method when the component is mounted
    });


    // Define the watch for form
    watch(form, (val) => {
      // Handle form changes
      triggerChange(val);
    }, { deep: true });

    // Define the watch for errors
    watch(errors, (val) => {
      // Handle errors changes
      checkError(val);
    }, { deep: true });

    // const formatMoneyFromNumber = (amount) => {
    //   return accounting.formatMoney(amount, currencyConfig.currency);
    // };

    const workLevelChanged = (work_level_id, index) => {
      form.work_level_model = props.levels[index];
      form.work_level_id = work_level_id;
    };

    const spacingTypeChanged = (type) => {
      form.spacing_type = type;
    };


    function changePageNumber(changeByValue) {
      var changeByValue = parseInt(changeByValue);
      var number_of_pages = parseInt(this.form.number_of_pages);
      if (number_of_pages == 0 && changeByValue < 1) {
        return false;
      }
      if (!Number.isInteger(changeByValue)) {
        return false;
      }
      this.form.number_of_pages = number_of_pages + changeByValue;
      this.validateNumberOfPages();
    };
    function changeNumberOfWords(changeByValue) {
       var changeByValue = parseInt(changeByValue);
       var number_of_words = parseInt(this.form.number_of_words);

       if (number_of_words == 0 && changeByValue < 1) {
         return false;
       }
       this.form.number_of_words = number_of_words + changeByValue;
       this.validateNumberOfWords();
    };
    function validateNumberOfWords(){
       if(this.form.number_of_words < this.form.service_model.minimum_order_quantity)
       {
         var minimum_order_quantity = this.form.service_model.minimum_order_quantity;
         this.$set(this.errors, "number_of_words", ['Minium order quantity is ' + minimum_order_quantity]);
       }
       else
       {
         this.$delete(this.errors, 'number_of_words');
       }
       this.$delete(this.errors, 'number_of_pages');
    };
    function validateNumberOfPages(){
      if(this.form.number_of_pages < this.form.service_model.minimum_order_quantity)
      {
        var minimum_order_quantity = this.form.service_model.minimum_order_quantity;
        errors.value.number_of_pages = ['Minium order quantity is ' + minimum_order_quantity];
      }
      else
      {
        delete this.errors.number_of_pages;
      }
      delete this.errors.number_of_words;
    };

    const getAdditionalServices = (service_model) =>  {
    console.log(service_model,'service_model');
      // Clear the errors
      errors.value = {};
      // Clear the added services
      // this.$set(this.form,'added_services', []);
      form.added_services = [];
      var service_id = service_model.id;
      var minimum_order_quantity = service_model.minimum_order_quantity;

      if(service_model.price_type_id == props.pricingTypes.perPage)
      {
        // this.form.number_of_pages = minimum_order_quantity;
        form.number_of_pages = minimum_order_quantity;
      }
      else
      {
        // this.form.number_of_pages = 1;
        form.number_of_pages = 1;
      }

      if(service_model.minimum_order_quantity)
      {
        // this.form.number_of_words = minimum_order_quantity;
        form.number_of_words = minimum_order_quantity;
      }
      else
      {
        // this.form.number_of_words = 500;
        form.number_of_words = 500;
      }

      var $scope = this;
      axios.post(props.additional_services_by_service_id_url, {
          service_id: service_id
        })
        .then(function(response) {
          // $scope.additional_services = response.data;
          additional_services.value = response.data;
        })
        .catch(function(error) {
          alert(error);
        });
    };

    function changeTab(tabNumber) {
      emit("changeTab", tabNumber);
    };
    function additionalServiceChanged(id, additionalService) {
      var isAlreadyInList = this.addedServiceList(id);

      if (isAlreadyInList) {
        this.form.added_services.splice(isAlreadyInList.key, 1);
      } else {
        this.form.added_services.push(additionalService);
      }
    };
    function addedServiceList(id) {
      var status = false;

      $.each(this.form.added_services, function(key, row) {
        if (row.id == id) {
          return (status = { key: key });
        }
      });

      return status;
    };
    function getServiceContainerClass(additionalServiceId) {
      return {
        "btn-orange": this.addedServiceList(additionalServiceId),
        "btn-outline-orange": !this.addedServiceList(additionalServiceId)
      };
    };
    const isNumber = (evt) =>  {
      evt = (evt) ? evt : window.event;
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      if ((charCode > 31 && (charCode < 48 || charCode > 57))) {
        evt.preventDefault();;
      } else {
        return true;
      }
    }

    function formatMoney (value) {
      return accounting.formatMoney(value, currencyConfig.currency);
    }
    const servicesTitle = props.services.map(service => service.name);
    const servicesObject = props.services.map((service) => {
    return {
     title: service.name,
     value:service
    }
    });
    const writersObject = props.writer_list.map((writer) => {
    return {
     title: writer.first_name,
     value:writer
    }
    });
    const urgenciesObject = props.urgencies.map((urgency) => {
    return {
     title: urgency.name,
     value:urgency
    }
    });
    console.log(props.urgencies,'urgencies');
    return {
      hasError,
      errors,
      additional_services,
      form,
      checkError,
      triggerChange,
      //formatMoneyFromNumber,
      workLevelChanged,
      spacingTypeChanged,
      formatMoney,
      serviceOptions,
      servicesObject,
      writersObject,
      urgenciesObject,
      changePageNumber,
      changeNumberOfWords,
      //  validateNumberOfWords,
      validateNumberOfPages,
      getAdditionalServices,
      changeTab,
      additionalServiceChanged,
      addedServiceList,
      getServiceContainerClass,
      isNumber,
    };
  },
};
</script>

<style lang="scss" scoped>
@import "~vue-multiselect/dist/vue-multiselect.min.css";

html {
  scroll-behavior: smooth;
}

@media (prefers-reduced-motion: reduce) {
  html {
    scroll-behavior: auto;
  }
}

input[type="number"] {
  -moz-appearance: textfield;
}
</style>

<!--  -->
