import html from "./template.html?raw";
import css from "./style.css?raw";
import { navigate } from "../../utils/router";

const template = document.createElement("template");
template.innerHTML = `<style>${css}</style>${html}`;

export class SplashArt extends HTMLElement {
  constructor() {
    super();
    this.attachShadow({ mode: "open" });
    this.shadowRoot?.appendChild(template.content.cloneNode(true));
  }

  connectedCallback() {
    // Seleciona o elemento dentro do shadow DOM
    const counterElement = this.shadowRoot?.getElementById(
      "splash-carregamento",
    );
    if (!counterElement) return;

    // Array que vai armazenar os pontos
    let counter: string[] = [];

    // Atualiza a cada 1 segundo
    window.setInterval(() => {
      if (counter.length >= 3) {
        counter = [];
      }
      counter.push(".");
      // Atualiza o conteúdo do span
      counterElement.textContent = counter.join("");
    }, 1000);

    setTimeout(() => {
      navigate("login");
    }, 3000);
  }
}

// Registra o Web Component
customElements.define("splash-art", SplashArt);
