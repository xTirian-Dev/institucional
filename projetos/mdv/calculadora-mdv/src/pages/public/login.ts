import { LoginForm } from "../../components/login-form";

/**
 * Renderiza a página Splash.
 * Neste momento, retorna apenas o componente.
 */
export function renderLogin(): HTMLElement {
  const container = document.createElement("div");

  // Cria o componente loginForm
  const loginForm = new LoginForm();

  // Adiciona ao container
  container.appendChild(loginForm);

  return container;
}
