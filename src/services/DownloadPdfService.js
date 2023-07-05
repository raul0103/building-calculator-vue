import axios from "axios";
import { useVariablesStore } from "@/stores/variables.js";

export default class DownloadPdfService {
  constructor() {
    this.variables_store = useVariablesStore();
    this.table_style = `
          table {
              width: 100%;
              margin-bottom: 20px;
              border: 1px solid #000;
              border-collapse: collapse;
              font-family: DejaVu Sans;
              font-size: 12px;
          }
  
          table th {
              font-weight: bold;
              padding: 5px;
              background: #efefef;
              border: 1px solid #000;
          }
  
          table td {
              border: 1px solid #000;
              padding: 5px;
          }
  
          table .bold{
            font-weight: bold;
          }
          table .center{
            text-align: center
          }
        `;
  }

  /**
   *
   * @param download - Скачать ли сгенерированный файл. Например при отправке формы мы генерируем файл на сервере но не скачиваем
   */
  async generatePDF(download = true) {
    let form_data = new FormData();
    let table_html = document.getElementById("calculator-table-pdf")?.innerHTML;
    if (!table_html) {
      console.error("Не удалось сгенерировать таблицу для PDF");
      return;
    }

    form_data.append(
      "table_html",
      `<style>${this.table_style}</style>${table_html}`
    );

    return await axios
      .post(
        `${this.variables_store.api_url}/calculator/api/pdf/pdf.php`,
        form_data
      )
      .then((response) => {
        if (download) {
          // Создаем ссылку для скачивания файла
          const download_link = document.createElement("a");
          download_link.href = `${this.variables_store.api_url}/calculator/api/pdf/download.php?filename=${response.data.filename}`;
          // Добавляем ссылку на страницу и автоматически кликаем по ней
          document.body.appendChild(download_link);
          download_link.click();
          document.body.removeChild(download_link);
        } else {
          return response.data.filename;
        }
      })
      .catch((error) => {
        console.error("Ошибка при генерации и сохранении PDF:", error);
      });
  }

  /** Отправит PDF сметы на почту пользователю */
  async sendPdfToUserEmail(user_email) {
    const smeta_pdf_name = await this.generatePDF(false);
    let form_data = new FormData();
    form_data.append("smeta_pdf_name", smeta_pdf_name);
    form_data.append("user_email", user_email);

    axios
      .post(
        `${this.variables_store.api_url}/calculator/api/message/send-user.php`,
        form_data
      )
      .then((response) => {
        if (response.data.status) {
          console.log("Сообщение пользователю отправено");
        } else {
          console.error("Ошибка при отправке сообщения на почту пользователя");
        }
      })
      .catch((error) => {
        console.error(
          "Ошибка при отправке сообщения на почту пользователя: ",
          error
        );
      });
  }
}
