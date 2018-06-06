export class Comment {
	constructor(public id: string, public commentId: string, public commentProfileId: string, public commentTrailId: string, public commentContent: string, public commentTimestamp: Date) {
	}
}