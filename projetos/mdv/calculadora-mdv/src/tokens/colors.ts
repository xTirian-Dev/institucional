/**
 * Paleta principal de cores do Design System.
 * Usada para backgrounds, botões, textos e elementos de destaque.
 */

export const colors = {
  /** Cor principal da aplicação */
  primary: "#CC092F",
  /** Cor de destaque/hover */
  secondary: "#444444",
  /** Cor de fundo da aplicação */
  background: "#F9F9F9",
  /** Cor de superfícies (cards, modais, etc.) */
  surface: "#D5D5D5",
  /** cor do menu lateral */
  sideboard: "DDDDDD",
  /** Cor padrão para textos */
  textPrimary: "#444444",
  /** Cor secundária para textos */
  textSecondary: "#000000",
  /** Texto em backgrounds escuros */
  textInverse: "#ffffff",
};

/**
 * Paleta completa combinando cores principais e neutras.
 */
export const pallet = {
  ...colors,
};
