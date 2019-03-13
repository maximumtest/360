<template>
  <div class="wrapper">
    <h1>{{ msg }}</h1>
    <div class="round-table">
      <div
        class="group b n"
        v-for="(group, index) in groups"
        :key="index"
      >
        <div
          v-for="user in group"
          :key="user.id"
          class="user"
          :class="{ 'user__active': currentUserId ? (currentUserId === user.id) : false }"
          @click="currentUserId = user.id"
        >
          <div
            class="user__name"
          >
            {{ user.name }}
          </div>

          <div
            class="user__feedback"
            v-if="currentUserId && !(currentUserId ? (currentUserId === user.id) : false)"
          >
            {{ user.feedback }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';
import axios from 'axios';

declare interface DefaultUser {
  id: number;
  name: string;
  username: string;
  email: string;
  address: {
    street: string;
    suite: string;
    city: string;
    zipcode: string;
    geo: {
      lat: string;
      lng: string;
    };
  };
  phone: string;
  website: string;
  company: {
    name: string;
    catchPhrase: string;
    bs: string;
  };
}

declare interface User {
  id: number;
  name: string;
  email: string;
  feedback: string;
}

type Groups = User[][];
type Group = User[];

@Component
export default class RoundTable extends Vue {
  @Prop() private msg!: string;

  // Data
  private groups = [] as Groups;
  private currentUserId = null as number|null;

  // Hooks
  public async mounted() {
    this.groups = this.splitUsersByTwo(await this.getUsers());
  }

  // Methods
  private async getUsers(): Promise<User[]> {
    const users = await axios.get('https://jsonplaceholder.typicode.com/users');
    return users.data.map((user: DefaultUser) => {
      return {
        id: user.id,
        name: user.name,
        email: user.email,
        feedback: `Я думаю, что ${user.company.catchPhrase}`,
      };
    });
  }

  private splitUsersByTwo(array: User[]): Groups {
    let i = 0;
    const chunks = array.length / 2;
    const sets: Groups = [];

    while (i < chunks) {
      const group: Group = array.splice(0, 2);
      sets[i] = group;
      i += 1;
    }

    return sets;
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss" scoped>
h3 {
  margin: 40px 0 0;
}

ul {
  list-style-type: none;
  padding: 0;
}

li {
  display: inline-block;
  margin: 0 10px;
}

a {
  color: #42b983;
}

.wrapper {
  align-items: center;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.round-table {
  background-color: #ddd;
  background-position: center;
  background-repeat: no-repeat;
  border-radius: 50%;
  border: 10px solid #333;
  border: 1px solid #000;
  height: 600px;
  overflow: hidden;
  width: 600px;
}

.group {
  display: flex;
  justify-content: space-between;
}

.user {
  align-items: center;
  border-radius: 50%;
  border: 1px solid black;
  cursor: pointer;
  display: flex;
  height: 100px;
  justify-content: center;
  width: 100px;
  word-break: break-word;

  &:hover {
    background-color: #1de9b6;
  }

  &__active {
    background-color: #1de9b6;
  }
}

.b {
  padding: 0;
  margin: 0;
  box-sizing: border-box;
  font-size: 16px;
  color: #000;
  font-family: consolas, monaco, menlo, courier, monospace;
  border: 0;
  line-height: normal;
}

.n {
  position: absolute;
  width: 580px;
  height: 22px;
  margin-top: 280px;
  transform-origin: center center;
  z-index: 2;
}

.n:first-child {
  transform: rotate(-60deg);
}

.n:first-child > .tn {
  transform: rotate(60deg);
}

.n:nth-child(2) {
  transform: rotate(-30deg);
}

.n:nth-child(2) > .tn {
  transform: rotate(30deg);
}

.n:nth-child(3) {
  transform: rotate(0);
}

.n:nth-child(3) > .tn {
  transform: rotate(0deg);
}

.n:nth-child(4) {
  transform: rotate(30deg);
}

.n:nth-child(4) > .tn {
  transform: rotate(-30deg);
}

.n:nth-child(5) {
  transform: rotate(60deg);
}

.n:nth-child(5) > .tn {
  transform: rotate(-60deg);
}

.n:nth-child(6) {
  transform: rotate(90deg);
}

.n:nth-child(6) > .tn {
  transform: rotate(-90deg);
}
</style>
