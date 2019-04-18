export interface User {
  _id: string;
  email: string;
  name?: string;
}

export interface AuthState {
  jwtToken?: string;
  expiresIn?: string;
  user?: User;
}

export interface LoginRequest {
  email: string;
  password: string;
}

export interface VerifyEmailRequest {
  password: string;
  password_confirmation: string;
  code: string;
}

export interface TokenResponse {
  access_token: string;
  expires_in?: string;
}
