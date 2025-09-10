import { renderLogin } from "../pages/public/login";
import { renderSplash } from "../pages/public/splash";

type Route = () => HTMLElement;

const routes: Record<string, Route> = {
  splashArt: renderSplash,
  login: renderLogin,
};

const app = document.querySelector<HTMLDivElement>("#app")!;

export function navigate(routeName: string) {
  const route = routes[routeName];
  if (!route) return;

  app.innerHTML = "";
  app.appendChild(route());
}
