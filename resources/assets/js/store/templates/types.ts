import { Question } from '../questions/types';

export interface Template {
  _id: string;
  name: string;
  author_id: string;
  question_ids: Array<string>;
  questions?: Array<Question>;
}

export interface TemplatesState {}
