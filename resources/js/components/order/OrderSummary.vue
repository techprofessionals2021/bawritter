<template>
  <div>
    <div class="card outlined br-20 shadow">
      <div class="card-body" v-if="!isObjectEmpty(form)">
        <h5 class="card-title sky font-weight-bold">Order Summary</h5>
        <div class="mb-4">
          <p>
            <b class="sky">Service</b>
            <br />
            {{ form.service_model.name }}
            <br />
            <small class="form-text text-muted">{{ form.work_level_model.name }} (Work level)</small>
          </p>
          <div>
            <b>Urgency</b>
            :
            {{ form.urgency_model.name }}
          </div>

          <div v-if="form.service_model.price_type_id == pricingTypes.fixed">
            <div>
              <b>Rate</b>
              :
              {{ formatMoney(form.unit_price) }}
            </div>
          </div>

          <div v-if="form.service_model.price_type_id == pricingTypes.perWord">
            <div>
              <b>Number of words</b>
              :
              {{ form.number_of_words }}
            </div>
            <div>
              <b>Rate</b>
              :
              {{ form.unit_price }}
            </div>
          </div>

          <div v-if="form.service_model.price_type_id == pricingTypes.perPage">
            <div>
              <b>Spacing Type</b>
              :
              {{ form.spacing_type }}
            </div>
            <div>
              <b>Pages</b>
              :
              {{ form.number_of_pages }}
            </div>

            <div>
              <b>Writer Fee</b>
              :
              {{ form.spacing_type == spacingTypes.double ?
              formatMoney(form?.writer_model?.staff_price?.double_space_price)
              :
              formatMoney(form?.writer_model?.staff_price?.single_space_price)
              }}
            </div>

            <div>
              <b>Work Level Charges</b>
              :
              {{ formatMoney(calculatePercentage(
                    form.spacing_type == spacingTypes.double ?   form?.writer_model?.staff_price?.double_space_price : form?.writer_model?.staff_price?.single_space_price,
                    form.work_level_model.percentage_to_add
                    ))
              }}
            </div>

            <div>
              <b>Urgency Charges</b>
              :
              {{ formatMoney(calculatePercentage(
                    form.spacing_type == spacingTypes.double ?   form?.writer_model?.staff_price?.double_space_price : form?.writer_model?.staff_price?.single_space_price,
                    form.urgency_model.percentage_to_add
                    ))
              }}
            </div>

            <div>
              <b>Unit Rate</b>
              :
              {{ formatMoney(form.unit_price) }}
            </div>
          </div>
        </div>

        <table class="table table-sm">
          <tbody>
            <tr>
              <th class="sky" scope="row" style="width: 30%;  ">Amount</th>
              <td style="width: 70%" class="text-right sky">{{ formatMoney(form.amount)   }}</td>
            </tr>
            <tr v-if="form.added_services.length > 0 ">
              <td colspan="2">
                <div>
                  <div class="sky font-weight-bold">Additional Services</div>
                  <div class="row" v-for="row in form.added_services" v-bind:key="row.id">
                    <div class="col-md-6">
                      <div style="padding-left: 10px;">{{ row.name }}</div>
                    </div>
                    <div class="col-md-6 text-right">{{ formatMoney(row.rate) }}</div>
                  </div>
                </div>
              </td>
            </tr>
            <tr>
              <th class="sky" scope="row" style="width: 30%">Total</th>
              <td  style="width: 80%" class="text-right sky">{{ calculateTotal }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, reactive,watch,Vue,computed,nextTick } from 'vue';
export default {
  props: {
    form: {
      default() {
        return null;
      }
    }
  },
  // filters: {
  //   formatMoney: function(value) {
  //     return accounting.formatMoney(value, currencyConfig.currency);
  //   }
  // },

  setup(props,{ emit }) {
    console.log(props,'props');
    const pricingTypes = {
      fixed: 1,
      perWord: 2,
      perPage: 3
    };

    const spacingTypes = {
      double: 'double',
      single: 'single'
    };

    function formatMoney(value) {
      return accounting.formatMoney(value, currencyConfig.currency);
    }

    function isObjectEmpty(obj) {
      return Object.keys(obj).length === 0 && obj.constructor === Object;
    }
    function calculatePercentage(basePrice, percentageToAdd) {
      var number = (parseFloat(basePrice) * parseFloat(percentageToAdd)) / 100;
      return Number(parseFloat(number).toFixed(2));
    }
    function formatMoneyFromNumber($amount) {
      return accounting.formatMoney($amount, currencyConfig.currency);
    }
    const calculateTotal = computed(()=> {
      if (!isObjectEmpty(props.form)) {
        console.log(props.form,'props.form');
        console.log(pricingTypes,'pricingTypes');
        const form = ref(props.form);
        var serviceModel = form.value.service_model;
        var writerModel = form.value.writer_model;
        // var pricingTypes = pricingTypes;
        // var spacingTypes = spacingTypes;
        var workLevelModel = form.value.work_level_model;
        var urgencyModel = form.value.urgency_model;


        // When Price Type is fixed
        if (serviceModel.price_type_id == pricingTypes.fixed) {
          var quantity = 1;
          var base_price = parseFloat(serviceModel.price);

        }
        // When Price Type is Per Word
        if (serviceModel.price_type_id == pricingTypes.perWord) {
          var quantity = parseFloat(form.value.number_of_words);
          var base_price = parseFloat(serviceModel.price);

        }
        // When Price Type is based on Number of Pages
        if (serviceModel.price_type_id == pricingTypes.perPage) {
          // alert(form.value.spacing_type)
          if (form.value.spacing_type == spacingTypes.double) {
            // If spacing type is double
            // var base_price = parseFloat(serviceModel.double_spacing_price);
            var base_price = parseFloat(writerModel?.staff_price?.double_space_price);
          } else {
            // If spacing type is single
            // var base_price = parseFloat(serviceModel.single_spacing_price);
            var base_price = parseFloat(writerModel?.staff_price?.single_space_price);

          }
          var quantity = parseFloat(form.value.number_of_pages);
        }
        // Calculate Work Level Price
        var work_level_price = calculatePercentage(
          base_price,
          workLevelModel.percentage_to_add
        );
        // Calculate Urgency Price
        var urgency_price = calculatePercentage(
          base_price,
          urgencyModel.percentage_to_add
        );
        // Calculate Unit Price
        var unit_price = Number(parseFloat(base_price + work_level_price + urgency_price)).toFixed(2);

        // Amount before including Additional Services
        var amount = (unit_price * quantity).toFixed(2);

        // Calculate Total Price of Additional Services
        let additional_services_cost = _.sumBy(form.value.added_services, function(row) {
          return parseFloat(row.rate);
        });

        // Calculate Sub Total  Amount + Additional Services
        var sub_total = (
          parseFloat(amount) + parseFloat(additional_services_cost)
        ).toFixed(2);

        // Total (work here if you need to add discount option)
        var total = sub_total;

        form.value.service_id = serviceModel.id;
        form.value.urgency_id = urgencyModel.id;
        form.value.urgency_percentage = urgencyModel.percentage_to_add;
        form.value.dead_line = urgencyModel.date;

        form.value.work_level_id = workLevelModel.id;
        form.value.work_level_percentage = workLevelModel.percentage_to_add;

        form.value.base_price = base_price;
        // unit price = base_price + work_level_price + urgency_price
        form.value.unit_price = unit_price;
        form.value.quantity = quantity;
        // amount = unit_price * quantity
        form.value.amount = amount;
        form.value.sub_total = sub_total;
        form.value.total = total;
        form.value.work_level_price = work_level_price;
        form.value.urgency_price = urgency_price;

        var $scope = this;

        nextTick(() => {
          const records = { ...form.value }; // Use object spread for shallow copy
          // Delete the following records before passing
          delete records['number_of_words'];
          delete records['number_of_pages'];
          delete records['service_model'];
          delete records['urgency_model'];
          delete records['work_level_model'];

          emit("dataChanged", records);
        });

        return formatMoneyFromNumber(total);
      }
    })

    return {
      pricingTypes,
      spacingTypes,
      formatMoney,
      calculateTotal,
      formatMoneyFromNumber,
      calculatePercentage,
      isObjectEmpty

    };
  },

};
</script>
