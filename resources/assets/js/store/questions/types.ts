export interface QuestionType {
    _id: string;
    name: string;
}

export interface Question {
  _id: string;
  question_type_id: string;
  text: string;
  answers?: Array<string>;
  question_type?: QuestionType;
}
