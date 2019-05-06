import { User } from '@/store/auth/types';

export interface ReviewItem {
  _id: string;
  created_at: string;
  manager_id: string;
  review_status_id: string;
  template_id: string;
  title: string;
  updated_at: string;
  users?: User[];
}

export interface ReviewState {
  reviews: ReviewItem[] | null;
}
