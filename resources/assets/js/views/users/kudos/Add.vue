<template>
  <form
    v-if="!isLoading"
    class="create-kudo-page"
    @submit.prevent="addKudos"
  >
    <md-field>
      <label>Категория</label>
      <md-select v-model="kudosRequest.kudos_category_id">
        <md-option
          v-for="kudosCategory in kudosCategories"
          :key="kudosCategory._id"
          :value="kudosCategory._id"
        >
          {{ kudosCategory.name }}
        </md-option>
      </md-select>
    </md-field>

    <md-chips v-model="kudosRequest.tags">
      <label>Теги</label>
    </md-chips>

    <md-field>
      <label>Текст</label>
      <md-textarea v-model="kudosRequest.text"/>
    </md-field>

    <md-button
      type="submit"
      class="md-raised md-accent create-kudo-page__submit-btn"
    >
      Отправить
    </md-button>
  </form>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import { namespace } from 'vuex-class';
import { name as kudosStoreName } from '@/store/kudos';
import { CreateKudosRequest, KudosCategory, KudosTag } from '@/store/kudos/types';

const Kudos = namespace(kudosStoreName);

@Component
export default class AddKudosPage extends Vue {
  @Kudos.Action getKudosCategories!: Function;

  @Kudos.Action getKudosTags!: Function;

  @Kudos.Action createKudos!: Function;

  kudosRequest: CreateKudosRequest = {
    text: undefined,
    kudos_category_id: undefined,
    tags: [],
    userToId: undefined,
  };

  isLoading: boolean = true;

  kudosCategories!: KudosCategory[];

  kudosTags!: KudosTag[];

  async created() {
    this.kudosRequest.userToId = this.$route.params.id;

    const kudosCategoriesResponse = await this.getKudosCategories();

    if (kudosCategoriesResponse.status > 400) {
      console.error('error while loading kudos categories', kudosCategoriesResponse);
    } else {
      this.kudosCategories = kudosCategoriesResponse.data;
    }

    this.isLoading = false;
  }

  async addKudos() {
    const { text, kudos_category_id, userToId } = this.kudosRequest;

    if (!text || !kudos_category_id) {
      return false;
    }

    const response = await this.createKudos(this.kudosRequest);

    if (response.status > 400) {
      console.error(response);
    } else {
      this.$router.push(`/users/${userToId}`);
    }
  }
}
</script>

<style lang="scss" scoped>
  .create-kudo-page {
    padding: 16px;

    &__submit-btn {
      margin-left: -2px;
    }
  }
</style>
