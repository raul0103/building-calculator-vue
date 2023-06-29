<template>
  <button class="calculator-button-primary" @click="submit">
    Получить смету
  </button>
</template>

<script>
import axios from "axios";

import { useCalculatorStore } from "@/stores/calculator.js";
import { useFieldsStore } from "@/stores/fields.js";
import { useVariablesStore } from "@/stores/variables.js";
import { useResultsStore } from "@/stores/results.js";

export default {
  data() {
    return {
      calculator_store: useCalculatorStore(),
      fields_store: useFieldsStore(),
      variables_store: useVariablesStore(),
      results_store: useResultsStore(),
    };
  },
  methods: {
    submit() {
      const form_name = this.calculator_store.calculator_form_name;
      const calculator_key_active = this.calculator_store.calculator_key_active;
      const form_error = this.fields_store.checkEmptyRequiredFields(form_name);

      if (form_error) {
        console.error("В форме содержатся ошибки");
        return;
      }

      axios
        .get(
          `${this.variables_store.api_url}/calculator/api/controllers/calculator.php`,
          {
            params: {
              form_error: form_error ? 1 : 0,
              calculator_key_active: calculator_key_active,
              values:
                this.fields_store.completed_fields[form_name][
                  calculator_key_active
                ],
            },
          }
        )
        .then((response) => {
          this.results_store.setResults(response.data);
        });
    },
  },
};
</script>
