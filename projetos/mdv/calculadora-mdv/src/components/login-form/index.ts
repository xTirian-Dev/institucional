import html from "./template.html?raw";
import css from "./style.css?raw";

const template = document.createElement("template");
template.innerHTML = `<style>${css}</style>${html}`;

export class LoginForm extends HTMLElement {
  constructor() {
    super();
    this.attachShadow({ mode: "open" });
    this.shadowRoot?.appendChild(template.content.cloneNode(true));
  }

  connectedCallback() {}
}

customElements.define("login-form", LoginForm);
