import { colors } from "../tokens/colors.ts";

/**
 * Tema claro (Light Theme)
 */
export const lightTheme = {
  primary: colors.primary,
  secondary: colors.secondary,
  background: colors.background,
  surface: colors.surface,
  sideboard: colors.sideboard,
  textPrimary: colors.textPrimary,
  textSecondary: colors.textSecondary,
  textInverse: colors.textInverse,
};

/**
 * Tema escuro (Dark Theme)
 */
export const darkTheme = {
  primary: "#CC092F", // mantém a cor principal
  secondary: "#AAAAAA", // mais claro para contraste em dark
  background: "#222222", // fundo escuro
  surface: "#333333", // superfícies escuras
  sideboard: "#111111", // menu lateral escuro
  textPrimary: "#FFFFFF", // texto principal claro
  textSecondary: "#DDDDDD", // texto secundário
  textInverse: "#000000", // texto para fundos claros (pouco usado)
};
