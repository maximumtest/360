export interface Kudos {
  _id: string;
  text: string;
  kudos_category_id: string;
  user_from_id: string;
  user_to_id: string;
}

export interface KudosTag {
  _id: string;
  name: string;
  author_id: string;
}

export interface KudosCategory {
  _id: string;
  name: string;
}

export interface KudosState {
  currentUserKudos: Kudos[] | null;
  kudosTags: KudosTag[] | null;
  kudosCategories: KudosCategory[] | null;
}

export interface CreateKudosRequest {
  text?: string;
  kudos_category_id?: string;
  tags?: string[];
  userToId?: string;
}
