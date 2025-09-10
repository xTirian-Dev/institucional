import { SplashArt } from "../../components/splash";

/**
 * Renderiza a página Splash.
 * Neste momento, retorna apenas o componente splash-art.
 */
export function renderSplash(): HTMLElement {
  const container = document.createElement("div");

  // Cria o componente splash-art
  const splash = new SplashArt();

  // Adiciona ao container
  container.appendChild(splash);

  return container;
}
