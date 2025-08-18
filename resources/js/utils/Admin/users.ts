import type {Permission, Role, User} from "@/types";

export type UserFormInner = {
    name: User['name'],
    email: User['email'],
    role: Role['name'] | null,
    permissions: Permission['name'][],
    password: string,
    password_confirmation: string,
};

export const newUserForm = (user?: User): UserFormInner => {
    let permissions = [];

    if (user !== undefined) {
        for (const permission of user.permissions) {
            permissions.push(permission.name);
        }
    }

    return {
        name: user?.name ?? '',
        email: user?.email ?? '',
        role: user?.role?.name ?? null,
        permissions,
        password: '',
        password_confirmation: '',
    }
}
