<template>
  <div>
    <div
      id="jsGrid"
      class="js-grid"
    />
  </div>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import { namespace } from 'vuex-class';
import { name as usersStoreName } from '@/store/users';
import { name as rolesStoreName } from '@/store/roles';
import { CreateUserRequest, UpdateUserRequest } from '@/store/users/types';
import { User } from '@/store/auth/types';
import { Role } from '@/store/roles/types';
import { AxiosResponse } from 'axios';

const Users = namespace(usersStoreName);
const Roles = namespace(rolesStoreName);

interface Filter {
  name: string;
  email: string;
  role_ids: string;
}

@Component
export default class AdminUsersPage extends Vue {
  @Users.Action getAllUsers!: () => AxiosResponse;
  @Users.Action createUser!: (request: CreateUserRequest) => AxiosResponse;
  @Users.Action updateUser!: (request: UpdateUserRequest) => AxiosResponse;
  @Users.Action deleteUser!: (userId: string) => AxiosResponse;
  @Roles.Action getRoles!: () => AxiosResponse;

  users: User[] = [];
  roles: Role[] = [];

  async mounted() {
    const { data: roles } = await this.getRoles();
    const { data: users } = await this.getAllUsers();

    this.roles = roles || [];
    this.users = users || [];

    this.enableJsGrid();
  }

  enableJsGrid() {
    $('#jsGrid').jsGrid({
      width: '100%',
      filtering: true,
      editing: true,
      sorting: true,
      autoload: true,
      inserting: true,
      deleteConfirm: 'Are you really crazy and wanna delete this useful entry?!',
      controller: {
        loadData: async ({ name, email, role_ids: roleId }: Filter) => {
          let result = [...this.users];

          if (name) {
            result = result.filter((user: User) => user.name && user.name.includes(name));
          }

          if (email) {
            result = result.filter((user: User) => user.email && user.email.includes(email));
          }

          if (roleId) {
            result = result.filter((user: User) => user.role_ids && user.role_ids!.includes(roleId));
          }

          return result;
        },
        insertItem: async (item: User) => {
          const request: CreateUserRequest = {
            email: item.email,
            role_id: <string>item.role_ids,
          };

          const response: AxiosResponse = await this.createUser(request);

          if (response.status >= 300) {
            console.error(response);
            throw new Error('Error while creating user');
          }

          return response.data;
        },
        updateItem: async (item: User) => {
          const request: UpdateUserRequest = {
            userId: item._id,
            email: item.email,
            role_id: <string>item.role_ids,
          };

          const response: AxiosResponse = await this.updateUser(request);

          if (response.status >= 300) {
            console.error(response);
            throw new Error('Error while updating user');
          }

          return response.data;
        },
        deleteItem: async (item: User) => {
          const response = await this.deleteUser(item._id);

          if (response.status >= 300) {
            console.error(response);
            throw new Error('Error while deleting user');
          }
        },
      },

      fields: [
        { name: 'name', title: 'Имя', type: 'text', validate: 'required', inserting: false, editing: false },
        { name: 'email', title: 'email', type: 'text', validate: 'required' },
        {
          name: 'avatar',
          title: 'Аватар',
          type: 'text',
          filtering: false,
          inserting: false,
          editing: false,
          sorting: false,
          align: 'center',
          itemTemplate(value: string, item: User) {
            if (!value) {
              return value;
            }

            const img = document.createElement('img');
            img.src = value;
            img.style.height = '50px';

            return img;
          },
        },
        {
          name: 'role_ids',
          title: 'Роль',
          type: 'select',
          items: [
            {},
            ...(this.roles || []),
          ],
          valueField: '_id',
          textField: 'name',
          validate: 'required',
          itemTemplate: (value: string[], item: User) => {
            if (!value || value.length === 0) {
              return '';
            }

            if (this.roles) {
              return this.roles.find(role => role._id === value[0])!.name;
            }

            return value[0];
          },
        },
        { type: "control" }
      ]
    });
  }
}
</script>

<style lang="scss" scoped>

</style>
