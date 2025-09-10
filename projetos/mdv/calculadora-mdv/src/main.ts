import "./style.css";
import { renderSplash } from "./pages/public/splash.ts";

const app = document.querySelector<HTMLDivElement>("#app")!;

app.appendChild(renderSplash());
