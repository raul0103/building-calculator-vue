/**
 * Работа с данными пользователя
 *
 * Например для сохранения данных из обратной формы для использования имени, номера и почты при регенерации PDF
 */

export default class LocalStorageService {
  constructor() {
    this.storage_key = "calculator-a23nof2cs";
  }

  getStorage(key = null) {
    const data = JSON.parse(localStorage.getItem(this.storage_key));

    if (key && data) {
      if (key in data) return data[key];
      else return null;
    } else {
      return data;
    }
  }

  setStorage(data) {
    const value = JSON.stringify(data);
    localStorage.setItem(this.storage_key, value);
  }
}
