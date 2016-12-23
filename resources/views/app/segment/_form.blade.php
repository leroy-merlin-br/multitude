<form data-module="GardenForm SegmentForm" action="{{ $formSubmit }}" method="{{ $formAction ?? 'post' }}">
    <div class="col-xs-12 col-md-6">
        <legend class="legend">Dados Básicos</legend>
        <div class="field">
            <input type="text" class="input" name="name" value="{{ $segment->name }}"/>
            <label for="name" class="label">Name</label>
            <span class="helper" aria-describedby="name">
                Name that describes the Segment.
            </span>
        </div>
        <div class="field">
            <input type="text" class="input" name="slug" value="{{ $segment->slug }}"/>
            <label for="slug" class="label">Slug</label>
            <span class="helper" aria-describedby="slug">
                Slug is an unique identifier for the given segment. It should not contain spaces, uppercase nor special characters.
            </span>
        </div>
    </div>
    <div class="col-xs-12 col-md-6">
        <legend class="legend">Update interval</legend>
        <div class="field">
            <select class="select" name="additionInterval" id="select">
                <option value="{{ $segment->additionInterval }}">{{ $segment->additionInterval }}</option>
                <option value="*/5 * * * *">Every 5 minutes</option>
                <option value="*/30 * * * *">Every 30 minutes</option>
                <option value="0 * * * *">Every hour</option>
                <option value="0 */3 * * *">Every 3 hours</option>
                <option value="0 */12 * * *">Every 12 hours</option>
                <option value="0 2 * * *">Every day</option>
                <option value="0 2 */3 * *">Every 3 days</option>
                <option value="0 2 */7 * *">Every week</option>
            </select>
            <label for="additionInterval" class="label">Interval to add new Customers</label>
            <span class="helper" aria-describedby="additionInterval">The interval in which the segment will evaluate and add new Customers.</span>
        </div>
        <div class="field">
            <select class="select" name="removalInterval" id="select">
                <option value="{{ $segment->removalInterval }}">{{ $segment->removalInterval }}</option>
                <option value="*/5 * * * *">Every 5 minutes</option>
                <option value="*/30 * * * *">Every 30 minutes</option>
                <option value="0 * * * *">Every hour</option>
                <option value="0 */3 * * *">Every 3 hours</option>
                <option value="0 */12 * * *">Every 12 hours</option>
                <option value="0 2 * * *">Every day</option>
                <option value="0 2 */3 * *">Every 3 days</option>
                <option value="0 2 */7 * *">Every week</option>
                <option value="0 2 1 * *">Every month</option>
                <option value="0 2 1 */3 *">Every 3 months</option>
                <option value="0 2 1 */6 *">Every 6 months</option>
                <option value="0 2 1 1 *">Every year</option>
            </select>
            <label for="removalInterval" class="label">Interval to remove Customers</label>
            <span class="helper" aria-describedby="removalInterval">The interval in which the segment will re-evaluate it's Customers and remove the ones that don't match anymore.</span>
        </div>
    </div>
    <div class="col-xs-12">
        <legend class="legend">Rules</legend>
        <div class="field">
            <span class="label">Ruleset to match Customers</span>
            <div data-module="QueryBuilder" data-initial-query='{!! $segment->ruleset() ? json_encode($segment->ruleset()->rules) : "{}" !!}'></div>
            <span class="helper">This is the ruleset that will be used to evaluate which Customers are part of the segment.</span>
        </div>
    </div>
    <div class="col-xs-12">
        <hr>
        <button class="button"
            data-module="PreviewQuery"
            data-querybuilder="[data-module=QueryBuilder]"
            data-endpoint="{{ route("customer.index") }}"
            data-previewbox="#queryPreview">
            Preview
        </button>
        <button class="button button-primary" data-xd>
            Save
        </button>
    </div>
</form>
