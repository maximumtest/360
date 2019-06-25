import { User } from '../auth/types';

export interface UsersState {
  users?: User[];
}

export interface CreateUserRequest {
  email: string;
  password?: string;
  role_id: string;
  department_id?: string;
}

export interface UpdateUserRequest {
  userId: string;
  email?: string;
  password?: string;
  role_id?: string;
  department_id?: string;
}
