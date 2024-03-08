<!--  -->
<template>
  <div>
    <form @submit.prevent>
      <div class="row">
        <div class="col-md-8">
          <div class="card shadow br-20">
            <div class="card-body">
              <div v-if="isActiveTab(1)">
                <ServiceSelection
                  :pricingTypes="pricingTypes"
                  :services="services"
                  :levels="levels"
                  :urgencies="urgencies"
                  :spacings="spacings"
                  :user_id="user_id"
                  :restricted_order_page_url="restricted_order_page_url"
                  :create_account_url="create_account_url"
                  :additional_services_by_service_id_url="additional_services_by_service_id_url"
                  :writer_list="writer_list"
                  @changeTab="changeTab"
                  @dataChanged="handleServiceSelection"
                ></ServiceSelection>
              </div>

              <!-- Tab 2 Starts -->
              <div v-if="isActiveTab(2)">
                <Instruction
                  :errors="errors"
                  :term_and_condition_url="term_and_condition_url"
                  :privacy_policy_url="privacy_policy_url"
                  :upload_attachment_url="upload_attachment_url"
                  @changeTab="changeTab"
                  @submitRequest="handleSubmit"
                ></Instruction>
              </div>
              <!-- Tab 2 Ends -->
            </div>
          </div>
        </div>
        <div class=" col-md-4">
          <div class="sticky-top">
            <OrderSummary :form="dataForOrderSummary" @dataChanged="handleCalculatedData"></OrderSummary>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import { ref } from 'vue';
import ServiceSelection from "./order/ServiceSelection.vue";
import Instruction from "./order/Instruction.vue";
import OrderSummary from "./order/OrderSummary.vue";

export default {
  components: {
    ServiceSelection,
    Instruction,
    OrderSummary
  },
  props: {
    services: {
      default: {}
    },
    levels: {
      type: Array,
      default() {
        return {};
      }
    },
    urgencies: {
      type: Array,
      default() {
        return {};
      }
    },
    spacings: {
      type: Array,
      default() {
        return {};
      }
    },
    user_id: {
      type: [Boolean, Number],
      default() {
        return null;
      }
    },
    restricted_order_page_url: {
      type: String,
      default() {
        return null;
      }
    },
    upload_attachment_url: {
      type: String,
      default() {
        return null;
      }
    },
    create_account_url: {
      type: String,
      default() {
        return null;
      }
    },
    additional_services_by_service_id_url: {
      type: String,
      default() {
        return null;
      }
    },
    add_to_cart_url: {
      type: String,
      default() {
        return null;
      }
    },
    term_and_condition_url: {
      type: String,
      default() {
        return null;
      }
    },
    privacy_policy_url: {
      type: String,
      default() {
        return null;
      }
    },
    writer_list: {
        default: {}
    },
  },
  setup(props) {
    const pricingTypes = ref({
      fixed: 1,
      perWord: 2,
      perPage: 3
    });
    const activeTab = ref(1);
    const form = ref({});
    const dataForOrderSummary = ref({});
    const errors = ref({});

    const handleServiceSelection = ($data) => {
      console.log($data,'$data');
      dataForOrderSummary.value = $data;
    };

    const handleCalculatedData = ($calculatedData) => {
      form.value = $calculatedData;
    };

    const handleSubmit = ($data) => {
      const mergedRecords = { ...form.value, ...$data };
      submitForm(mergedRecords);
    };

    const changeTab = (tabNumber) => {
      if (tabNumber === 2 && !props.user_id) {
        window.location = props.restricted_order_page_url;
        return false;
      }
      activeTab.value = tabNumber;
      window.scrollTo(0, 0);
    };

    const isActiveTab = (tab) => {
      return activeTab.value === tab;
    };

    const submitForm = (formRecords) => {
      errors.value = [];
      var $scope = this;

      axios
        .post(props.add_to_cart_url, formRecords)
        .then(function(response) {
          if (response.data.redirect_url) {
            window.location.href = response.data.redirect_url;
          } else if (response.data.errors) {
            $scope.errors = response.data.errors;
          } else {
            alert("Something went wrong");
          }
        })
        .catch(function(error) {
          console.log(error);
          alert("Something went wrongs");
        });
    };

    return {
      pricingTypes,
      activeTab,
      form,
      dataForOrderSummary,
      errors,
      handleServiceSelection,
      handleCalculatedData,
      handleSubmit,
      changeTab,
      isActiveTab
    };
  }
};
</script>


<!--  -->
