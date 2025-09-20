export enum RoleEnum {
	Admin = 'admin',
	User = 'user',
}

export enum PermissionEnum {
	USERS_VIEW = 'users_view',
	USERS_CREATE = 'users_create',
	USERS_EDIT = 'users_edit',
	USERS_DELETE = 'users_delete',
	USERS_IMPERSONATE = 'users_impersonate',
}
export enum ToastTypeEnum {
	Success = 'success',
	Info = 'info',
	Warning = 'warning',
	Error = 'error',
}
export enum ChangeTypeEnum {
	Creation = 'creation',
	Update = 'update',
	Deletion = 'deletion',
	Restoration = 'restoration',
}