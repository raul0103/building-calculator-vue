import axios from "axios";
import { useCalculatorStore } from "@/stores/calculator.js";
import { useFieldsStore } from "@/stores/fields.js";
import { useVariablesStore } from "@/stores/variables.js";
import { useResultsStore } from "@/stores/results.js";
import LocalStorageService from "@/services/LocalStorageService.js";

export default class CalculatorService {
  constructor() {
    this.calculator_store = useCalculatorStore();
    this.fields_store = useFieldsStore();
    this.variables_store = useVariablesStore();
    this.results_store = useResultsStore();
    this.local_storage_service = new LocalStorageService();
  }

  /**
   * Отправляет данные для расчета
   *
   * @param check_sent_callback - Проверять отправил ли пользователь обратную форму
   */
  async calculate(check_sent_callback = false) {
    // Если Стоит проверка отправлена ли форма и она не отправлена тогда остановить скрипт
    if (check_sent_callback) {
      if (!this.local_storage_service.getStorage("callback-form")) {
        return;
      }
    }

    const calculate_form_name = this.calculator_store.calculator_form_name;
    const calculate_field_error =
      this.fields_store.checkEmptyRequiredFields(calculate_form_name);

    if (calculate_field_error) {
      console.warn("В форме содержатся ошибки");
      this.results_store.setResults(null);
      return;
    }

    const calculator_key_active = this.calculator_store.calculator_key_active;
    await axios
      .get(`${this.variables_store.api_url}/calculator/api/calculator.php`, {
        params: {
          form_error: calculate_field_error ? 1 : 0,
          calculator_key_active: calculator_key_active,
          values:
            this.fields_store.completed_fields[calculate_form_name][
              calculator_key_active
            ],
        },
      })
      .then((response) => {
        this.results_store.setResults(response.data);
      });
  }
}
