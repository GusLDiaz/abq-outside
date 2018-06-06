//reference for http-request style messages

export class Status {
	constructor(public status: number, public message: string, public type: string) {}
}