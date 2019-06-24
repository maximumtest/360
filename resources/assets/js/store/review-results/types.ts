export interface Answer {
  question_id: string;
  answer: string | Array<string>;
}

export interface ReviewResult {
  _id?: string;
  review_id: string;
  respondent_id: string;
  answers: Array<Answer>;
}

export interface CreateReviewResultRequest {
  review_id: string;
  respondent_id: string;
  answers: Array<Answer>;
}

export interface ReviewResultsState {
  reviewResults: ReviewResult[];
}
