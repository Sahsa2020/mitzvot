const fs = require('fs');
const path = require('path');
const ConcatPlugin = require('webpack-concat-plugin');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const ProgressPlugin = require('webpack/lib/ProgressPlugin');
const CircularDependencyPlugin = require('circular-dependency-plugin');
const HtmlWebpackPlugin = require('html-webpack-plugin');
const ExtractTextPlugin = require('extract-text-webpack-plugin');
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
const autoprefixer = require('autoprefixer');
const postcssUrl = require('postcss-url');
const cssnano = require('cssnano');
const customProperties = require('postcss-custom-properties');

const { NoEmitOnErrorsPlugin, EnvironmentPlugin, HashedModuleIdsPlugin } = require('webpack');
const { InsertConcatAssetsWebpackPlugin, BaseHrefWebpackPlugin, SuppressExtractedTextChunksWebpackPlugin } = require('@angular/cli/plugins/webpack');
const { CommonsChunkPlugin, ModuleConcatenationPlugin } = require('webpack').optimize;
const { LicenseWebpackPlugin } = require('license-webpack-plugin');
const { AngularCompilerPlugin } = require('@ngtools/webpack');

const nodeModules = path.join(process.cwd(), 'node_modules');
const realNodeModules = fs.realpathSync(nodeModules);
const genDirNodeModules = path.join(process.cwd(), 'src', '$$_gendir', 'node_modules');
const entryPoints = ["inline","polyfills","sw-register","styles","vendor","main"];
const minimizeCss = true;
const baseHref = "";
const deployUrl = "";
const postcssPlugins = function () {
        // safe settings based on: https://github.com/ben-eb/cssnano/issues/358#issuecomment-283696193
        const importantCommentRe = /@preserve|@license|[@#]\s*source(?:Mapping)?URL|^!/i;
        const minimizeOptions = {
            autoprefixer: false,
            safe: true,
            mergeLonghand: false,
            discardComments: { remove: (comment) => !importantCommentRe.test(comment) }
        };
        return [
            postcssUrl({
                url: (URL) => {
                    // Only convert root relative URLs, which CSS-Loader won't process into require().
                    if (!URL.startsWith('/') || URL.startsWith('//')) {
                        return URL;
                    }
                    if (deployUrl.match(/:\/\//)) {
                        // If deployUrl contains a scheme, ignore baseHref use deployUrl as is.
                        return `${deployUrl.replace(/\/$/, '')}${URL}`;
                    }
                    else if (baseHref.match(/:\/\//)) {
                        // If baseHref contains a scheme, include it as is.
                        return baseHref.replace(/\/$/, '') +
                            `/${deployUrl}/${URL}`.replace(/\/\/+/g, '/');
                    }
                    else {
                        // Join together base-href, deploy-url and the original URL.
                        // Also dedupe multiple slashes into single ones.
                        return `/${baseHref}/${deployUrl}/${URL}`.replace(/\/\/+/g, '/');
                    }
                }
            }),
            autoprefixer(),
            customProperties({ preserve: true })
        ].concat(minimizeCss ? [cssnano(minimizeOptions)] : []);
    };




module.exports = {
  "resolve": {
    "extensions": [
      ".ts",
      ".js"
    ],
    "modules": [
      "./node_modules",
      "./node_modules"
    ],
    "symlinks": true,
    "alias": {
      "rxjs/AsyncSubject": nodeModules + "/rxjs/_esm5/AsyncSubject.js",
      "rxjs/BehaviorSubject": nodeModules + "/rxjs/_esm5/BehaviorSubject.js",
      "rxjs/InnerSubscriber": nodeModules + "/rxjs/_esm5/InnerSubscriber.js",
      "rxjs/Notification": nodeModules + "/rxjs/_esm5/Notification.js",
      "rxjs/Observable": nodeModules + "/rxjs/_esm5/Observable.js",
      "rxjs/Observer": nodeModules + "/rxjs/_esm5/Observer.js",
      "rxjs/Operator": nodeModules + "/rxjs/_esm5/Operator.js",
      "rxjs/OuterSubscriber": nodeModules + "/rxjs/_esm5/OuterSubscriber.js",
      "rxjs/ReplaySubject": nodeModules + "/rxjs/_esm5/ReplaySubject.js",
      "rxjs/Rx": nodeModules + "/rxjs/_esm5/Rx.js",
      "rxjs/Scheduler": nodeModules + "/rxjs/_esm5/Scheduler.js",
      "rxjs/Subject": nodeModules + "/rxjs/_esm5/Subject.js",
      "rxjs/SubjectSubscription": nodeModules + "/rxjs/_esm5/SubjectSubscription.js",
      "rxjs/Subscriber": nodeModules + "/rxjs/_esm5/Subscriber.js",
      "rxjs/Subscription": nodeModules + "/rxjs/_esm5/Subscription.js",
      "rxjs/add/observable/bindCallback": nodeModules + "/rxjs/_esm5/add/observable/bindCallback.js",
      "rxjs/add/observable/bindNodeCallback": nodeModules + "/rxjs/_esm5/add/observable/bindNodeCallback.js",
      "rxjs/add/observable/combineLatest": nodeModules + "/rxjs/_esm5/add/observable/combineLatest.js",
      "rxjs/add/observable/concat": nodeModules + "/rxjs/_esm5/add/observable/concat.js",
      "rxjs/add/observable/defer": nodeModules + "/rxjs/_esm5/add/observable/defer.js",
      "rxjs/add/observable/dom/ajax": nodeModules + "/rxjs/_esm5/add/observable/dom/ajax.js",
      "rxjs/add/observable/dom/webSocket": nodeModules + "/rxjs/_esm5/add/observable/dom/webSocket.js",
      "rxjs/add/observable/empty": nodeModules + "/rxjs/_esm5/add/observable/empty.js",
      "rxjs/add/observable/forkJoin": nodeModules + "/rxjs/_esm5/add/observable/forkJoin.js",
      "rxjs/add/observable/from": nodeModules + "/rxjs/_esm5/add/observable/from.js",
      "rxjs/add/observable/fromEvent": nodeModules + "/rxjs/_esm5/add/observable/fromEvent.js",
      "rxjs/add/observable/fromEventPattern": nodeModules + "/rxjs/_esm5/add/observable/fromEventPattern.js",
      "rxjs/add/observable/fromPromise": nodeModules + "/rxjs/_esm5/add/observable/fromPromise.js",
      "rxjs/add/observable/generate": nodeModules + "/rxjs/_esm5/add/observable/generate.js",
      "rxjs/add/observable/if": nodeModules + "/rxjs/_esm5/add/observable/if.js",
      "rxjs/add/observable/interval": nodeModules + "/rxjs/_esm5/add/observable/interval.js",
      "rxjs/add/observable/merge": nodeModules + "/rxjs/_esm5/add/observable/merge.js",
      "rxjs/add/observable/never": nodeModules + "/rxjs/_esm5/add/observable/never.js",
      "rxjs/add/observable/of": nodeModules + "/rxjs/_esm5/add/observable/of.js",
      "rxjs/add/observable/onErrorResumeNext": nodeModules + "/rxjs/_esm5/add/observable/onErrorResumeNext.js",
      "rxjs/add/observable/pairs": nodeModules + "/rxjs/_esm5/add/observable/pairs.js",
      "rxjs/add/observable/race": nodeModules + "/rxjs/_esm5/add/observable/race.js",
      "rxjs/add/observable/range": nodeModules + "/rxjs/_esm5/add/observable/range.js",
      "rxjs/add/observable/throw": nodeModules + "/rxjs/_esm5/add/observable/throw.js",
      "rxjs/add/observable/timer": nodeModules + "/rxjs/_esm5/add/observable/timer.js",
      "rxjs/add/observable/using": nodeModules + "/rxjs/_esm5/add/observable/using.js",
      "rxjs/add/observable/zip": nodeModules + "/rxjs/_esm5/add/observable/zip.js",
      "rxjs/add/operator/audit": nodeModules + "/rxjs/_esm5/add/operator/audit.js",
      "rxjs/add/operator/auditTime": nodeModules + "/rxjs/_esm5/add/operator/auditTime.js",
      "rxjs/add/operator/buffer": nodeModules + "/rxjs/_esm5/add/operator/buffer.js",
      "rxjs/add/operator/bufferCount": nodeModules + "/rxjs/_esm5/add/operator/bufferCount.js",
      "rxjs/add/operator/bufferTime": nodeModules + "/rxjs/_esm5/add/operator/bufferTime.js",
      "rxjs/add/operator/bufferToggle": nodeModules + "/rxjs/_esm5/add/operator/bufferToggle.js",
      "rxjs/add/operator/bufferWhen": nodeModules + "/rxjs/_esm5/add/operator/bufferWhen.js",
      "rxjs/add/operator/catch": nodeModules + "/rxjs/_esm5/add/operator/catch.js",
      "rxjs/add/operator/combineAll": nodeModules + "/rxjs/_esm5/add/operator/combineAll.js",
      "rxjs/add/operator/combineLatest": nodeModules + "/rxjs/_esm5/add/operator/combineLatest.js",
      "rxjs/add/operator/concat": nodeModules + "/rxjs/_esm5/add/operator/concat.js",
      "rxjs/add/operator/concatAll": nodeModules + "/rxjs/_esm5/add/operator/concatAll.js",
      "rxjs/add/operator/concatMap": nodeModules + "/rxjs/_esm5/add/operator/concatMap.js",
      "rxjs/add/operator/concatMapTo": nodeModules + "/rxjs/_esm5/add/operator/concatMapTo.js",
      "rxjs/add/operator/count": nodeModules + "/rxjs/_esm5/add/operator/count.js",
      "rxjs/add/operator/debounce": nodeModules + "/rxjs/_esm5/add/operator/debounce.js",
      "rxjs/add/operator/debounceTime": nodeModules + "/rxjs/_esm5/add/operator/debounceTime.js",
      "rxjs/add/operator/defaultIfEmpty": nodeModules + "/rxjs/_esm5/add/operator/defaultIfEmpty.js",
      "rxjs/add/operator/delay": nodeModules + "/rxjs/_esm5/add/operator/delay.js",
      "rxjs/add/operator/delayWhen": nodeModules + "/rxjs/_esm5/add/operator/delayWhen.js",
      "rxjs/add/operator/dematerialize": nodeModules + "/rxjs/_esm5/add/operator/dematerialize.js",
      "rxjs/add/operator/distinct": nodeModules + "/rxjs/_esm5/add/operator/distinct.js",
      "rxjs/add/operator/distinctUntilChanged": nodeModules + "/rxjs/_esm5/add/operator/distinctUntilChanged.js",
      "rxjs/add/operator/distinctUntilKeyChanged": nodeModules + "/rxjs/_esm5/add/operator/distinctUntilKeyChanged.js",
      "rxjs/add/operator/do": nodeModules + "/rxjs/_esm5/add/operator/do.js",
      "rxjs/add/operator/elementAt": nodeModules + "/rxjs/_esm5/add/operator/elementAt.js",
      "rxjs/add/operator/every": nodeModules + "/rxjs/_esm5/add/operator/every.js",
      "rxjs/add/operator/exhaust": nodeModules + "/rxjs/_esm5/add/operator/exhaust.js",
      "rxjs/add/operator/exhaustMap": nodeModules + "/rxjs/_esm5/add/operator/exhaustMap.js",
      "rxjs/add/operator/expand": nodeModules + "/rxjs/_esm5/add/operator/expand.js",
      "rxjs/add/operator/filter": nodeModules + "/rxjs/_esm5/add/operator/filter.js",
      "rxjs/add/operator/finally": nodeModules + "/rxjs/_esm5/add/operator/finally.js",
      "rxjs/add/operator/find": nodeModules + "/rxjs/_esm5/add/operator/find.js",
      "rxjs/add/operator/findIndex": nodeModules + "/rxjs/_esm5/add/operator/findIndex.js",
      "rxjs/add/operator/first": nodeModules + "/rxjs/_esm5/add/operator/first.js",
      "rxjs/add/operator/groupBy": nodeModules + "/rxjs/_esm5/add/operator/groupBy.js",
      "rxjs/add/operator/ignoreElements": nodeModules + "/rxjs/_esm5/add/operator/ignoreElements.js",
      "rxjs/add/operator/isEmpty": nodeModules + "/rxjs/_esm5/add/operator/isEmpty.js",
      "rxjs/add/operator/last": nodeModules + "/rxjs/_esm5/add/operator/last.js",
      "rxjs/add/operator/let": nodeModules + "/rxjs/_esm5/add/operator/let.js",
      "rxjs/add/operator/map": nodeModules + "/rxjs/_esm5/add/operator/map.js",
      "rxjs/add/operator/mapTo": nodeModules + "/rxjs/_esm5/add/operator/mapTo.js",
      "rxjs/add/operator/materialize": nodeModules + "/rxjs/_esm5/add/operator/materialize.js",
      "rxjs/add/operator/max": nodeModules + "/rxjs/_esm5/add/operator/max.js",
      "rxjs/add/operator/merge": nodeModules + "/rxjs/_esm5/add/operator/merge.js",
      "rxjs/add/operator/mergeAll": nodeModules + "/rxjs/_esm5/add/operator/mergeAll.js",
      "rxjs/add/operator/mergeMap": nodeModules + "/rxjs/_esm5/add/operator/mergeMap.js",
      "rxjs/add/operator/mergeMapTo": nodeModules + "/rxjs/_esm5/add/operator/mergeMapTo.js",
      "rxjs/add/operator/mergeScan": nodeModules + "/rxjs/_esm5/add/operator/mergeScan.js",
      "rxjs/add/operator/min": nodeModules + "/rxjs/_esm5/add/operator/min.js",
      "rxjs/add/operator/multicast": nodeModules + "/rxjs/_esm5/add/operator/multicast.js",
      "rxjs/add/operator/observeOn": nodeModules + "/rxjs/_esm5/add/operator/observeOn.js",
      "rxjs/add/operator/onErrorResumeNext": nodeModules + "/rxjs/_esm5/add/operator/onErrorResumeNext.js",
      "rxjs/add/operator/pairwise": nodeModules + "/rxjs/_esm5/add/operator/pairwise.js",
      "rxjs/add/operator/partition": nodeModules + "/rxjs/_esm5/add/operator/partition.js",
      "rxjs/add/operator/pluck": nodeModules + "/rxjs/_esm5/add/operator/pluck.js",
      "rxjs/add/operator/publish": nodeModules + "/rxjs/_esm5/add/operator/publish.js",
      "rxjs/add/operator/publishBehavior": nodeModules + "/rxjs/_esm5/add/operator/publishBehavior.js",
      "rxjs/add/operator/publishLast": nodeModules + "/rxjs/_esm5/add/operator/publishLast.js",
      "rxjs/add/operator/publishReplay": nodeModules + "/rxjs/_esm5/add/operator/publishReplay.js",
      "rxjs/add/operator/race": nodeModules + "/rxjs/_esm5/add/operator/race.js",
      "rxjs/add/operator/reduce": nodeModules + "/rxjs/_esm5/add/operator/reduce.js",
      "rxjs/add/operator/repeat": nodeModules + "/rxjs/_esm5/add/operator/repeat.js",
      "rxjs/add/operator/repeatWhen": nodeModules + "/rxjs/_esm5/add/operator/repeatWhen.js",
      "rxjs/add/operator/retry": nodeModules + "/rxjs/_esm5/add/operator/retry.js",
      "rxjs/add/operator/retryWhen": nodeModules + "/rxjs/_esm5/add/operator/retryWhen.js",
      "rxjs/add/operator/sample": nodeModules + "/rxjs/_esm5/add/operator/sample.js",
      "rxjs/add/operator/sampleTime": nodeModules + "/rxjs/_esm5/add/operator/sampleTime.js",
      "rxjs/add/operator/scan": nodeModules + "/rxjs/_esm5/add/operator/scan.js",
      "rxjs/add/operator/sequenceEqual": nodeModules + "/rxjs/_esm5/add/operator/sequenceEqual.js",
      "rxjs/add/operator/share": nodeModules + "/rxjs/_esm5/add/operator/share.js",
      "rxjs/add/operator/shareReplay": nodeModules + "/rxjs/_esm5/add/operator/shareReplay.js",
      "rxjs/add/operator/single": nodeModules + "/rxjs/_esm5/add/operator/single.js",
      "rxjs/add/operator/skip": nodeModules + "/rxjs/_esm5/add/operator/skip.js",
      "rxjs/add/operator/skipLast": nodeModules + "/rxjs/_esm5/add/operator/skipLast.js",
      "rxjs/add/operator/skipUntil": nodeModules + "/rxjs/_esm5/add/operator/skipUntil.js",
      "rxjs/add/operator/skipWhile": nodeModules + "/rxjs/_esm5/add/operator/skipWhile.js",
      "rxjs/add/operator/startWith": nodeModules + "/rxjs/_esm5/add/operator/startWith.js",
      "rxjs/add/operator/subscribeOn": nodeModules + "/rxjs/_esm5/add/operator/subscribeOn.js",
      "rxjs/add/operator/switch": nodeModules + "/rxjs/_esm5/add/operator/switch.js",
      "rxjs/add/operator/switchMap": nodeModules + "/rxjs/_esm5/add/operator/switchMap.js",
      "rxjs/add/operator/switchMapTo": nodeModules + "/rxjs/_esm5/add/operator/switchMapTo.js",
      "rxjs/add/operator/take": nodeModules + "/rxjs/_esm5/add/operator/take.js",
      "rxjs/add/operator/takeLast": nodeModules + "/rxjs/_esm5/add/operator/takeLast.js",
      "rxjs/add/operator/takeUntil": nodeModules + "/rxjs/_esm5/add/operator/takeUntil.js",
      "rxjs/add/operator/takeWhile": nodeModules + "/rxjs/_esm5/add/operator/takeWhile.js",
      "rxjs/add/operator/throttle": nodeModules + "/rxjs/_esm5/add/operator/throttle.js",
      "rxjs/add/operator/throttleTime": nodeModules + "/rxjs/_esm5/add/operator/throttleTime.js",
      "rxjs/add/operator/timeInterval": nodeModules + "/rxjs/_esm5/add/operator/timeInterval.js",
      "rxjs/add/operator/timeout": nodeModules + "/rxjs/_esm5/add/operator/timeout.js",
      "rxjs/add/operator/timeoutWith": nodeModules + "/rxjs/_esm5/add/operator/timeoutWith.js",
      "rxjs/add/operator/timestamp": nodeModules + "/rxjs/_esm5/add/operator/timestamp.js",
      "rxjs/add/operator/toArray": nodeModules + "/rxjs/_esm5/add/operator/toArray.js",
      "rxjs/add/operator/toPromise": nodeModules + "/rxjs/_esm5/add/operator/toPromise.js",
      "rxjs/add/operator/window": nodeModules + "/rxjs/_esm5/add/operator/window.js",
      "rxjs/add/operator/windowCount": nodeModules + "/rxjs/_esm5/add/operator/windowCount.js",
      "rxjs/add/operator/windowTime": nodeModules + "/rxjs/_esm5/add/operator/windowTime.js",
      "rxjs/add/operator/windowToggle": nodeModules + "/rxjs/_esm5/add/operator/windowToggle.js",
      "rxjs/add/operator/windowWhen": nodeModules + "/rxjs/_esm5/add/operator/windowWhen.js",
      "rxjs/add/operator/withLatestFrom": nodeModules + "/rxjs/_esm5/add/operator/withLatestFrom.js",
      "rxjs/add/operator/zip": nodeModules + "/rxjs/_esm5/add/operator/zip.js",
      "rxjs/add/operator/zipAll": nodeModules + "/rxjs/_esm5/add/operator/zipAll.js",
      "rxjs/interfaces": nodeModules + "/rxjs/_esm5/interfaces.js",
      "rxjs/observable/ArrayLikeObservable": nodeModules + "/rxjs/_esm5/observable/ArrayLikeObservable.js",
      "rxjs/observable/ArrayObservable": nodeModules + "/rxjs/_esm5/observable/ArrayObservable.js",
      "rxjs/observable/BoundCallbackObservable": nodeModules + "/rxjs/_esm5/observable/BoundCallbackObservable.js",
      "rxjs/observable/BoundNodeCallbackObservable": nodeModules + "/rxjs/_esm5/observable/BoundNodeCallbackObservable.js",
      "rxjs/observable/ConnectableObservable": nodeModules + "/rxjs/_esm5/observable/ConnectableObservable.js",
      "rxjs/observable/DeferObservable": nodeModules + "/rxjs/_esm5/observable/DeferObservable.js",
      "rxjs/observable/EmptyObservable": nodeModules + "/rxjs/_esm5/observable/EmptyObservable.js",
      "rxjs/observable/ErrorObservable": nodeModules + "/rxjs/_esm5/observable/ErrorObservable.js",
      "rxjs/observable/ForkJoinObservable": nodeModules + "/rxjs/_esm5/observable/ForkJoinObservable.js",
      "rxjs/observable/FromEventObservable": nodeModules + "/rxjs/_esm5/observable/FromEventObservable.js",
      "rxjs/observable/FromEventPatternObservable": nodeModules + "/rxjs/_esm5/observable/FromEventPatternObservable.js",
      "rxjs/observable/FromObservable": nodeModules + "/rxjs/_esm5/observable/FromObservable.js",
      "rxjs/observable/GenerateObservable": nodeModules + "/rxjs/_esm5/observable/GenerateObservable.js",
      "rxjs/observable/IfObservable": nodeModules + "/rxjs/_esm5/observable/IfObservable.js",
      "rxjs/observable/IntervalObservable": nodeModules + "/rxjs/_esm5/observable/IntervalObservable.js",
      "rxjs/observable/IteratorObservable": nodeModules + "/rxjs/_esm5/observable/IteratorObservable.js",
      "rxjs/observable/NeverObservable": nodeModules + "/rxjs/_esm5/observable/NeverObservable.js",
      "rxjs/observable/PairsObservable": nodeModules + "/rxjs/_esm5/observable/PairsObservable.js",
      "rxjs/observable/PromiseObservable": nodeModules + "/rxjs/_esm5/observable/PromiseObservable.js",
      "rxjs/observable/RangeObservable": nodeModules + "/rxjs/_esm5/observable/RangeObservable.js",
      "rxjs/observable/ScalarObservable": nodeModules + "/rxjs/_esm5/observable/ScalarObservable.js",
      "rxjs/observable/SubscribeOnObservable": nodeModules + "/rxjs/_esm5/observable/SubscribeOnObservable.js",
      "rxjs/observable/TimerObservable": nodeModules + "/rxjs/_esm5/observable/TimerObservable.js",
      "rxjs/observable/UsingObservable": nodeModules + "/rxjs/_esm5/observable/UsingObservable.js",
      "rxjs/observable/bindCallback": nodeModules + "/rxjs/_esm5/observable/bindCallback.js",
      "rxjs/observable/bindNodeCallback": nodeModules + "/rxjs/_esm5/observable/bindNodeCallback.js",
      "rxjs/observable/combineLatest": nodeModules + "/rxjs/_esm5/observable/combineLatest.js",
      "rxjs/observable/concat": nodeModules + "/rxjs/_esm5/observable/concat.js",
      "rxjs/observable/defer": nodeModules + "/rxjs/_esm5/observable/defer.js",
      "rxjs/observable/dom/AjaxObservable": nodeModules + "/rxjs/_esm5/observable/dom/AjaxObservable.js",
      "rxjs/observable/dom/WebSocketSubject": nodeModules + "/rxjs/_esm5/observable/dom/WebSocketSubject.js",
      "rxjs/observable/dom/ajax": nodeModules + "/rxjs/_esm5/observable/dom/ajax.js",
      "rxjs/observable/dom/webSocket": nodeModules + "/rxjs/_esm5/observable/dom/webSocket.js",
      "rxjs/observable/empty": nodeModules + "/rxjs/_esm5/observable/empty.js",
      "rxjs/observable/forkJoin": nodeModules + "/rxjs/_esm5/observable/forkJoin.js",
      "rxjs/observable/from": nodeModules + "/rxjs/_esm5/observable/from.js",
      "rxjs/observable/fromEvent": nodeModules + "/rxjs/_esm5/observable/fromEvent.js",
      "rxjs/observable/fromEventPattern": nodeModules + "/rxjs/_esm5/observable/fromEventPattern.js",
      "rxjs/observable/fromPromise": nodeModules + "/rxjs/_esm5/observable/fromPromise.js",
      "rxjs/observable/generate": nodeModules + "/rxjs/_esm5/observable/generate.js",
      "rxjs/observable/if": nodeModules + "/rxjs/_esm5/observable/if.js",
      "rxjs/observable/interval": nodeModules + "/rxjs/_esm5/observable/interval.js",
      "rxjs/observable/merge": nodeModules + "/rxjs/_esm5/observable/merge.js",
      "rxjs/observable/never": nodeModules + "/rxjs/_esm5/observable/never.js",
      "rxjs/observable/of": nodeModules + "/rxjs/_esm5/observable/of.js",
      "rxjs/observable/onErrorResumeNext": nodeModules + "/rxjs/_esm5/observable/onErrorResumeNext.js",
      "rxjs/observable/pairs": nodeModules + "/rxjs/_esm5/observable/pairs.js",
      "rxjs/observable/race": nodeModules + "/rxjs/_esm5/observable/race.js",
      "rxjs/observable/range": nodeModules + "/rxjs/_esm5/observable/range.js",
      "rxjs/observable/throw": nodeModules + "/rxjs/_esm5/observable/throw.js",
      "rxjs/observable/timer": nodeModules + "/rxjs/_esm5/observable/timer.js",
      "rxjs/observable/using": nodeModules + "/rxjs/_esm5/observable/using.js",
      "rxjs/observable/zip": nodeModules + "/rxjs/_esm5/observable/zip.js",
      "rxjs/operator/audit": nodeModules + "/rxjs/_esm5/operator/audit.js",
      "rxjs/operator/auditTime": nodeModules + "/rxjs/_esm5/operator/auditTime.js",
      "rxjs/operator/buffer": nodeModules + "/rxjs/_esm5/operator/buffer.js",
      "rxjs/operator/bufferCount": nodeModules + "/rxjs/_esm5/operator/bufferCount.js",
      "rxjs/operator/bufferTime": nodeModules + "/rxjs/_esm5/operator/bufferTime.js",
      "rxjs/operator/bufferToggle": nodeModules + "/rxjs/_esm5/operator/bufferToggle.js",
      "rxjs/operator/bufferWhen": nodeModules + "/rxjs/_esm5/operator/bufferWhen.js",
      "rxjs/operator/catch": nodeModules + "/rxjs/_esm5/operator/catch.js",
      "rxjs/operator/combineAll": nodeModules + "/rxjs/_esm5/operator/combineAll.js",
      "rxjs/operator/combineLatest": nodeModules + "/rxjs/_esm5/operator/combineLatest.js",
      "rxjs/operator/concat": nodeModules + "/rxjs/_esm5/operator/concat.js",
      "rxjs/operator/concatAll": nodeModules + "/rxjs/_esm5/operator/concatAll.js",
      "rxjs/operator/concatMap": nodeModules + "/rxjs/_esm5/operator/concatMap.js",
      "rxjs/operator/concatMapTo": nodeModules + "/rxjs/_esm5/operator/concatMapTo.js",
      "rxjs/operator/count": nodeModules + "/rxjs/_esm5/operator/count.js",
      "rxjs/operator/debounce": nodeModules + "/rxjs/_esm5/operator/debounce.js",
      "rxjs/operator/debounceTime": nodeModules + "/rxjs/_esm5/operator/debounceTime.js",
      "rxjs/operator/defaultIfEmpty": nodeModules + "/rxjs/_esm5/operator/defaultIfEmpty.js",
      "rxjs/operator/delay": nodeModules + "/rxjs/_esm5/operator/delay.js",
      "rxjs/operator/delayWhen": nodeModules + "/rxjs/_esm5/operator/delayWhen.js",
      "rxjs/operator/dematerialize": nodeModules + "/rxjs/_esm5/operator/dematerialize.js",
      "rxjs/operator/distinct": nodeModules + "/rxjs/_esm5/operator/distinct.js",
      "rxjs/operator/distinctUntilChanged": nodeModules + "/rxjs/_esm5/operator/distinctUntilChanged.js",
      "rxjs/operator/distinctUntilKeyChanged": nodeModules + "/rxjs/_esm5/operator/distinctUntilKeyChanged.js",
      "rxjs/operator/do": nodeModules + "/rxjs/_esm5/operator/do.js",
      "rxjs/operator/elementAt": nodeModules + "/rxjs/_esm5/operator/elementAt.js",
      "rxjs/operator/every": nodeModules + "/rxjs/_esm5/operator/every.js",
      "rxjs/operator/exhaust": nodeModules + "/rxjs/_esm5/operator/exhaust.js",
      "rxjs/operator/exhaustMap": nodeModules + "/rxjs/_esm5/operator/exhaustMap.js",
      "rxjs/operator/expand": nodeModules + "/rxjs/_esm5/operator/expand.js",
      "rxjs/operator/filter": nodeModules + "/rxjs/_esm5/operator/filter.js",
      "rxjs/operator/finally": nodeModules + "/rxjs/_esm5/operator/finally.js",
      "rxjs/operator/find": nodeModules + "/rxjs/_esm5/operator/find.js",
      "rxjs/operator/findIndex": nodeModules + "/rxjs/_esm5/operator/findIndex.js",
      "rxjs/operator/first": nodeModules + "/rxjs/_esm5/operator/first.js",
      "rxjs/operator/groupBy": nodeModules + "/rxjs/_esm5/operator/groupBy.js",
      "rxjs/operator/ignoreElements": nodeModules + "/rxjs/_esm5/operator/ignoreElements.js",
      "rxjs/operator/isEmpty": nodeModules + "/rxjs/_esm5/operator/isEmpty.js",
      "rxjs/operator/last": nodeModules + "/rxjs/_esm5/operator/last.js",
      "rxjs/operator/let": nodeModules + "/rxjs/_esm5/operator/let.js",
      "rxjs/operator/map": nodeModules + "/rxjs/_esm5/operator/map.js",
      "rxjs/operator/mapTo": nodeModules + "/rxjs/_esm5/operator/mapTo.js",
      "rxjs/operator/materialize": nodeModules + "/rxjs/_esm5/operator/materialize.js",
      "rxjs/operator/max": nodeModules + "/rxjs/_esm5/operator/max.js",
      "rxjs/operator/merge": nodeModules + "/rxjs/_esm5/operator/merge.js",
      "rxjs/operator/mergeAll": nodeModules + "/rxjs/_esm5/operator/mergeAll.js",
      "rxjs/operator/mergeMap": nodeModules + "/rxjs/_esm5/operator/mergeMap.js",
      "rxjs/operator/mergeMapTo": nodeModules + "/rxjs/_esm5/operator/mergeMapTo.js",
      "rxjs/operator/mergeScan": nodeModules + "/rxjs/_esm5/operator/mergeScan.js",
      "rxjs/operator/min": nodeModules + "/rxjs/_esm5/operator/min.js",
      "rxjs/operator/multicast": nodeModules + "/rxjs/_esm5/operator/multicast.js",
      "rxjs/operator/observeOn": nodeModules + "/rxjs/_esm5/operator/observeOn.js",
      "rxjs/operator/onErrorResumeNext": nodeModules + "/rxjs/_esm5/operator/onErrorResumeNext.js",
      "rxjs/operator/pairwise": nodeModules + "/rxjs/_esm5/operator/pairwise.js",
      "rxjs/operator/partition": nodeModules + "/rxjs/_esm5/operator/partition.js",
      "rxjs/operator/pluck": nodeModules + "/rxjs/_esm5/operator/pluck.js",
      "rxjs/operator/publish": nodeModules + "/rxjs/_esm5/operator/publish.js",
      "rxjs/operator/publishBehavior": nodeModules + "/rxjs/_esm5/operator/publishBehavior.js",
      "rxjs/operator/publishLast": nodeModules + "/rxjs/_esm5/operator/publishLast.js",
      "rxjs/operator/publishReplay": nodeModules + "/rxjs/_esm5/operator/publishReplay.js",
      "rxjs/operator/race": nodeModules + "/rxjs/_esm5/operator/race.js",
      "rxjs/operator/reduce": nodeModules + "/rxjs/_esm5/operator/reduce.js",
      "rxjs/operator/repeat": nodeModules + "/rxjs/_esm5/operator/repeat.js",
      "rxjs/operator/repeatWhen": nodeModules + "/rxjs/_esm5/operator/repeatWhen.js",
      "rxjs/operator/retry": nodeModules + "/rxjs/_esm5/operator/retry.js",
      "rxjs/operator/retryWhen": nodeModules + "/rxjs/_esm5/operator/retryWhen.js",
      "rxjs/operator/sample": nodeModules + "/rxjs/_esm5/operator/sample.js",
      "rxjs/operator/sampleTime": nodeModules + "/rxjs/_esm5/operator/sampleTime.js",
      "rxjs/operator/scan": nodeModules + "/rxjs/_esm5/operator/scan.js",
      "rxjs/operator/sequenceEqual": nodeModules + "/rxjs/_esm5/operator/sequenceEqual.js",
      "rxjs/operator/share": nodeModules + "/rxjs/_esm5/operator/share.js",
      "rxjs/operator/shareReplay": nodeModules + "/rxjs/_esm5/operator/shareReplay.js",
      "rxjs/operator/single": nodeModules + "/rxjs/_esm5/operator/single.js",
      "rxjs/operator/skip": nodeModules + "/rxjs/_esm5/operator/skip.js",
      "rxjs/operator/skipLast": nodeModules + "/rxjs/_esm5/operator/skipLast.js",
      "rxjs/operator/skipUntil": nodeModules + "/rxjs/_esm5/operator/skipUntil.js",
      "rxjs/operator/skipWhile": nodeModules + "/rxjs/_esm5/operator/skipWhile.js",
      "rxjs/operator/startWith": nodeModules + "/rxjs/_esm5/operator/startWith.js",
      "rxjs/operator/subscribeOn": nodeModules + "/rxjs/_esm5/operator/subscribeOn.js",
      "rxjs/operator/switch": nodeModules + "/rxjs/_esm5/operator/switch.js",
      "rxjs/operator/switchMap": nodeModules + "/rxjs/_esm5/operator/switchMap.js",
      "rxjs/operator/switchMapTo": nodeModules + "/rxjs/_esm5/operator/switchMapTo.js",
      "rxjs/operator/take": nodeModules + "/rxjs/_esm5/operator/take.js",
      "rxjs/operator/takeLast": nodeModules + "/rxjs/_esm5/operator/takeLast.js",
      "rxjs/operator/takeUntil": nodeModules + "/rxjs/_esm5/operator/takeUntil.js",
      "rxjs/operator/takeWhile": nodeModules + "/rxjs/_esm5/operator/takeWhile.js",
      "rxjs/operator/throttle": nodeModules + "/rxjs/_esm5/operator/throttle.js",
      "rxjs/operator/throttleTime": nodeModules + "/rxjs/_esm5/operator/throttleTime.js",
      "rxjs/operator/timeInterval": nodeModules + "/rxjs/_esm5/operator/timeInterval.js",
      "rxjs/operator/timeout": nodeModules + "/rxjs/_esm5/operator/timeout.js",
      "rxjs/operator/timeoutWith": nodeModules + "/rxjs/_esm5/operator/timeoutWith.js",
      "rxjs/operator/timestamp": nodeModules + "/rxjs/_esm5/operator/timestamp.js",
      "rxjs/operator/toArray": nodeModules + "/rxjs/_esm5/operator/toArray.js",
      "rxjs/operator/toPromise": nodeModules + "/rxjs/_esm5/operator/toPromise.js",
      "rxjs/operator/window": nodeModules + "/rxjs/_esm5/operator/window.js",
      "rxjs/operator/windowCount": nodeModules + "/rxjs/_esm5/operator/windowCount.js",
      "rxjs/operator/windowTime": nodeModules + "/rxjs/_esm5/operator/windowTime.js",
      "rxjs/operator/windowToggle": nodeModules + "/rxjs/_esm5/operator/windowToggle.js",
      "rxjs/operator/windowWhen": nodeModules + "/rxjs/_esm5/operator/windowWhen.js",
      "rxjs/operator/withLatestFrom": nodeModules + "/rxjs/_esm5/operator/withLatestFrom.js",
      "rxjs/operator/zip": nodeModules + "/rxjs/_esm5/operator/zip.js",
      "rxjs/operator/zipAll": nodeModules + "/rxjs/_esm5/operator/zipAll.js",
      "rxjs/operators/audit": nodeModules + "/rxjs/_esm5/operators/audit.js",
      "rxjs/operators/auditTime": nodeModules + "/rxjs/_esm5/operators/auditTime.js",
      "rxjs/operators/buffer": nodeModules + "/rxjs/_esm5/operators/buffer.js",
      "rxjs/operators/bufferCount": nodeModules + "/rxjs/_esm5/operators/bufferCount.js",
      "rxjs/operators/bufferTime": nodeModules + "/rxjs/_esm5/operators/bufferTime.js",
      "rxjs/operators/bufferToggle": nodeModules + "/rxjs/_esm5/operators/bufferToggle.js",
      "rxjs/operators/bufferWhen": nodeModules + "/rxjs/_esm5/operators/bufferWhen.js",
      "rxjs/operators/catchError": nodeModules + "/rxjs/_esm5/operators/catchError.js",
      "rxjs/operators/combineAll": nodeModules + "/rxjs/_esm5/operators/combineAll.js",
      "rxjs/operators/combineLatest": nodeModules + "/rxjs/_esm5/operators/combineLatest.js",
      "rxjs/operators/concat": nodeModules + "/rxjs/_esm5/operators/concat.js",
      "rxjs/operators/concatAll": nodeModules + "/rxjs/_esm5/operators/concatAll.js",
      "rxjs/operators/concatMap": nodeModules + "/rxjs/_esm5/operators/concatMap.js",
      "rxjs/operators/concatMapTo": nodeModules + "/rxjs/_esm5/operators/concatMapTo.js",
      "rxjs/operators/count": nodeModules + "/rxjs/_esm5/operators/count.js",
      "rxjs/operators/debounce": nodeModules + "/rxjs/_esm5/operators/debounce.js",
      "rxjs/operators/debounceTime": nodeModules + "/rxjs/_esm5/operators/debounceTime.js",
      "rxjs/operators/defaultIfEmpty": nodeModules + "/rxjs/_esm5/operators/defaultIfEmpty.js",
      "rxjs/operators/delay": nodeModules + "/rxjs/_esm5/operators/delay.js",
      "rxjs/operators/delayWhen": nodeModules + "/rxjs/_esm5/operators/delayWhen.js",
      "rxjs/operators/dematerialize": nodeModules + "/rxjs/_esm5/operators/dematerialize.js",
      "rxjs/operators/distinct": nodeModules + "/rxjs/_esm5/operators/distinct.js",
      "rxjs/operators/distinctUntilChanged": nodeModules + "/rxjs/_esm5/operators/distinctUntilChanged.js",
      "rxjs/operators/distinctUntilKeyChanged": nodeModules + "/rxjs/_esm5/operators/distinctUntilKeyChanged.js",
      "rxjs/operators/elementAt": nodeModules + "/rxjs/_esm5/operators/elementAt.js",
      "rxjs/operators/every": nodeModules + "/rxjs/_esm5/operators/every.js",
      "rxjs/operators/exhaust": nodeModules + "/rxjs/_esm5/operators/exhaust.js",
      "rxjs/operators/exhaustMap": nodeModules + "/rxjs/_esm5/operators/exhaustMap.js",
      "rxjs/operators/expand": nodeModules + "/rxjs/_esm5/operators/expand.js",
      "rxjs/operators/filter": nodeModules + "/rxjs/_esm5/operators/filter.js",
      "rxjs/operators/finalize": nodeModules + "/rxjs/_esm5/operators/finalize.js",
      "rxjs/operators/find": nodeModules + "/rxjs/_esm5/operators/find.js",
      "rxjs/operators/findIndex": nodeModules + "/rxjs/_esm5/operators/findIndex.js",
      "rxjs/operators/first": nodeModules + "/rxjs/_esm5/operators/first.js",
      "rxjs/operators/groupBy": nodeModules + "/rxjs/_esm5/operators/groupBy.js",
      "rxjs/operators/ignoreElements": nodeModules + "/rxjs/_esm5/operators/ignoreElements.js",
      "rxjs/operators/index": nodeModules + "/rxjs/_esm5/operators/index.js",
      "rxjs/operators/isEmpty": nodeModules + "/rxjs/_esm5/operators/isEmpty.js",
      "rxjs/operators/last": nodeModules + "/rxjs/_esm5/operators/last.js",
      "rxjs/operators/map": nodeModules + "/rxjs/_esm5/operators/map.js",
      "rxjs/operators/mapTo": nodeModules + "/rxjs/_esm5/operators/mapTo.js",
      "rxjs/operators/materialize": nodeModules + "/rxjs/_esm5/operators/materialize.js",
      "rxjs/operators/max": nodeModules + "/rxjs/_esm5/operators/max.js",
      "rxjs/operators/merge": nodeModules + "/rxjs/_esm5/operators/merge.js",
      "rxjs/operators/mergeAll": nodeModules + "/rxjs/_esm5/operators/mergeAll.js",
      "rxjs/operators/mergeMap": nodeModules + "/rxjs/_esm5/operators/mergeMap.js",
      "rxjs/operators/mergeMapTo": nodeModules + "/rxjs/_esm5/operators/mergeMapTo.js",
      "rxjs/operators/mergeScan": nodeModules + "/rxjs/_esm5/operators/mergeScan.js",
      "rxjs/operators/min": nodeModules + "/rxjs/_esm5/operators/min.js",
      "rxjs/operators/multicast": nodeModules + "/rxjs/_esm5/operators/multicast.js",
      "rxjs/operators/observeOn": nodeModules + "/rxjs/_esm5/operators/observeOn.js",
      "rxjs/operators/onErrorResumeNext": nodeModules + "/rxjs/_esm5/operators/onErrorResumeNext.js",
      "rxjs/operators/pairwise": nodeModules + "/rxjs/_esm5/operators/pairwise.js",
      "rxjs/operators/partition": nodeModules + "/rxjs/_esm5/operators/partition.js",
      "rxjs/operators/pluck": nodeModules + "/rxjs/_esm5/operators/pluck.js",
      "rxjs/operators/publish": nodeModules + "/rxjs/_esm5/operators/publish.js",
      "rxjs/operators/publishBehavior": nodeModules + "/rxjs/_esm5/operators/publishBehavior.js",
      "rxjs/operators/publishLast": nodeModules + "/rxjs/_esm5/operators/publishLast.js",
      "rxjs/operators/publishReplay": nodeModules + "/rxjs/_esm5/operators/publishReplay.js",
      "rxjs/operators/race": nodeModules + "/rxjs/_esm5/operators/race.js",
      "rxjs/operators/reduce": nodeModules + "/rxjs/_esm5/operators/reduce.js",
      "rxjs/operators/refCount": nodeModules + "/rxjs/_esm5/operators/refCount.js",
      "rxjs/operators/repeat": nodeModules + "/rxjs/_esm5/operators/repeat.js",
      "rxjs/operators/repeatWhen": nodeModules + "/rxjs/_esm5/operators/repeatWhen.js",
      "rxjs/operators/retry": nodeModules + "/rxjs/_esm5/operators/retry.js",
      "rxjs/operators/retryWhen": nodeModules + "/rxjs/_esm5/operators/retryWhen.js",
      "rxjs/operators/sample": nodeModules + "/rxjs/_esm5/operators/sample.js",
      "rxjs/operators/sampleTime": nodeModules + "/rxjs/_esm5/operators/sampleTime.js",
      "rxjs/operators/scan": nodeModules + "/rxjs/_esm5/operators/scan.js",
      "rxjs/operators/sequenceEqual": nodeModules + "/rxjs/_esm5/operators/sequenceEqual.js",
      "rxjs/operators/share": nodeModules + "/rxjs/_esm5/operators/share.js",
      "rxjs/operators/shareReplay": nodeModules + "/rxjs/_esm5/operators/shareReplay.js",
      "rxjs/operators/single": nodeModules + "/rxjs/_esm5/operators/single.js",
      "rxjs/operators/skip": nodeModules + "/rxjs/_esm5/operators/skip.js",
      "rxjs/operators/skipLast": nodeModules + "/rxjs/_esm5/operators/skipLast.js",
      "rxjs/operators/skipUntil": nodeModules + "/rxjs/_esm5/operators/skipUntil.js",
      "rxjs/operators/skipWhile": nodeModules + "/rxjs/_esm5/operators/skipWhile.js",
      "rxjs/operators/startWith": nodeModules + "/rxjs/_esm5/operators/startWith.js",
      "rxjs/operators/subscribeOn": nodeModules + "/rxjs/_esm5/operators/subscribeOn.js",
      "rxjs/operators/switchAll": nodeModules + "/rxjs/_esm5/operators/switchAll.js",
      "rxjs/operators/switchMap": nodeModules + "/rxjs/_esm5/operators/switchMap.js",
      "rxjs/operators/switchMapTo": nodeModules + "/rxjs/_esm5/operators/switchMapTo.js",
      "rxjs/operators/take": nodeModules + "/rxjs/_esm5/operators/take.js",
      "rxjs/operators/takeLast": nodeModules + "/rxjs/_esm5/operators/takeLast.js",
      "rxjs/operators/takeUntil": nodeModules + "/rxjs/_esm5/operators/takeUntil.js",
      "rxjs/operators/takeWhile": nodeModules + "/rxjs/_esm5/operators/takeWhile.js",
      "rxjs/operators/tap": nodeModules + "/rxjs/_esm5/operators/tap.js",
      "rxjs/operators/throttle": nodeModules + "/rxjs/_esm5/operators/throttle.js",
      "rxjs/operators/throttleTime": nodeModules + "/rxjs/_esm5/operators/throttleTime.js",
      "rxjs/operators/timeInterval": nodeModules + "/rxjs/_esm5/operators/timeInterval.js",
      "rxjs/operators/timeout": nodeModules + "/rxjs/_esm5/operators/timeout.js",
      "rxjs/operators/timeoutWith": nodeModules + "/rxjs/_esm5/operators/timeoutWith.js",
      "rxjs/operators/timestamp": nodeModules + "/rxjs/_esm5/operators/timestamp.js",
      "rxjs/operators/toArray": nodeModules + "/rxjs/_esm5/operators/toArray.js",
      "rxjs/operators/window": nodeModules + "/rxjs/_esm5/operators/window.js",
      "rxjs/operators/windowCount": nodeModules + "/rxjs/_esm5/operators/windowCount.js",
      "rxjs/operators/windowTime": nodeModules + "/rxjs/_esm5/operators/windowTime.js",
      "rxjs/operators/windowToggle": nodeModules + "/rxjs/_esm5/operators/windowToggle.js",
      "rxjs/operators/windowWhen": nodeModules + "/rxjs/_esm5/operators/windowWhen.js",
      "rxjs/operators/withLatestFrom": nodeModules + "/rxjs/_esm5/operators/withLatestFrom.js",
      "rxjs/operators/zip": nodeModules + "/rxjs/_esm5/operators/zip.js",
      "rxjs/operators/zipAll": nodeModules + "/rxjs/_esm5/operators/zipAll.js",
      "rxjs/scheduler/Action": nodeModules + "/rxjs/_esm5/scheduler/Action.js",
      "rxjs/scheduler/AnimationFrameAction": nodeModules + "/rxjs/_esm5/scheduler/AnimationFrameAction.js",
      "rxjs/scheduler/AnimationFrameScheduler": nodeModules + "/rxjs/_esm5/scheduler/AnimationFrameScheduler.js",
      "rxjs/scheduler/AsapAction": nodeModules + "/rxjs/_esm5/scheduler/AsapAction.js",
      "rxjs/scheduler/AsapScheduler": nodeModules + "/rxjs/_esm5/scheduler/AsapScheduler.js",
      "rxjs/scheduler/AsyncAction": nodeModules + "/rxjs/_esm5/scheduler/AsyncAction.js",
      "rxjs/scheduler/AsyncScheduler": nodeModules + "/rxjs/_esm5/scheduler/AsyncScheduler.js",
      "rxjs/scheduler/QueueAction": nodeModules + "/rxjs/_esm5/scheduler/QueueAction.js",
      "rxjs/scheduler/QueueScheduler": nodeModules + "/rxjs/_esm5/scheduler/QueueScheduler.js",
      "rxjs/scheduler/VirtualTimeScheduler": nodeModules + "/rxjs/_esm5/scheduler/VirtualTimeScheduler.js",
      "rxjs/scheduler/animationFrame": nodeModules + "/rxjs/_esm5/scheduler/animationFrame.js",
      "rxjs/scheduler/asap": nodeModules + "/rxjs/_esm5/scheduler/asap.js",
      "rxjs/scheduler/async": nodeModules + "/rxjs/_esm5/scheduler/async.js",
      "rxjs/scheduler/queue": nodeModules + "/rxjs/_esm5/scheduler/queue.js",
      "rxjs/symbol/iterator": nodeModules + "/rxjs/_esm5/symbol/iterator.js",
      "rxjs/symbol/observable": nodeModules + "/rxjs/_esm5/symbol/observable.js",
      "rxjs/symbol/rxSubscriber": nodeModules + "/rxjs/_esm5/symbol/rxSubscriber.js",
      "rxjs/testing/ColdObservable": nodeModules + "/rxjs/_esm5/testing/ColdObservable.js",
      "rxjs/testing/HotObservable": nodeModules + "/rxjs/_esm5/testing/HotObservable.js",
      "rxjs/testing/SubscriptionLog": nodeModules + "/rxjs/_esm5/testing/SubscriptionLog.js",
      "rxjs/testing/SubscriptionLoggable": nodeModules + "/rxjs/_esm5/testing/SubscriptionLoggable.js",
      "rxjs/testing/TestMessage": nodeModules + "/rxjs/_esm5/testing/TestMessage.js",
      "rxjs/testing/TestScheduler": nodeModules + "/rxjs/_esm5/testing/TestScheduler.js",
      "rxjs/util/AnimationFrame": nodeModules + "/rxjs/_esm5/util/AnimationFrame.js",
      "rxjs/util/ArgumentOutOfRangeError": nodeModules + "/rxjs/_esm5/util/ArgumentOutOfRangeError.js",
      "rxjs/util/EmptyError": nodeModules + "/rxjs/_esm5/util/EmptyError.js",
      "rxjs/util/FastMap": nodeModules + "/rxjs/_esm5/util/FastMap.js",
      "rxjs/util/Immediate": nodeModules + "/rxjs/_esm5/util/Immediate.js",
      "rxjs/util/Map": nodeModules + "/rxjs/_esm5/util/Map.js",
      "rxjs/util/MapPolyfill": nodeModules + "/rxjs/_esm5/util/MapPolyfill.js",
      "rxjs/util/ObjectUnsubscribedError": nodeModules + "/rxjs/_esm5/util/ObjectUnsubscribedError.js",
      "rxjs/util/Set": nodeModules + "/rxjs/_esm5/util/Set.js",
      "rxjs/util/TimeoutError": nodeModules + "/rxjs/_esm5/util/TimeoutError.js",
      "rxjs/util/UnsubscriptionError": nodeModules + "/rxjs/_esm5/util/UnsubscriptionError.js",
      "rxjs/util/applyMixins": nodeModules + "/rxjs/_esm5/util/applyMixins.js",
      "rxjs/util/assign": nodeModules + "/rxjs/_esm5/util/assign.js",
      "rxjs/util/errorObject": nodeModules + "/rxjs/_esm5/util/errorObject.js",
      "rxjs/util/identity": nodeModules + "/rxjs/_esm5/util/identity.js",
      "rxjs/util/isArray": nodeModules + "/rxjs/_esm5/util/isArray.js",
      "rxjs/util/isArrayLike": nodeModules + "/rxjs/_esm5/util/isArrayLike.js",
      "rxjs/util/isDate": nodeModules + "/rxjs/_esm5/util/isDate.js",
      "rxjs/util/isFunction": nodeModules + "/rxjs/_esm5/util/isFunction.js",
      "rxjs/util/isNumeric": nodeModules + "/rxjs/_esm5/util/isNumeric.js",
      "rxjs/util/isObject": nodeModules + "/rxjs/_esm5/util/isObject.js",
      "rxjs/util/isPromise": nodeModules + "/rxjs/_esm5/util/isPromise.js",
      "rxjs/util/isScheduler": nodeModules + "/rxjs/_esm5/util/isScheduler.js",
      "rxjs/util/noop": nodeModules + "/rxjs/_esm5/util/noop.js",
      "rxjs/util/not": nodeModules + "/rxjs/_esm5/util/not.js",
      "rxjs/util/pipe": nodeModules + "/rxjs/_esm5/util/pipe.js",
      "rxjs/util/root": nodeModules + "/rxjs/_esm5/util/root.js",
      "rxjs/util/subscribeToResult": nodeModules + "/rxjs/_esm5/util/subscribeToResult.js",
      "rxjs/util/toSubscriber": nodeModules + "/rxjs/_esm5/util/toSubscriber.js",
      "rxjs/util/tryCatch": nodeModules + "/rxjs/_esm5/util/tryCatch.js",
      "rxjs/operators": nodeModules + "/rxjs/_esm5/operators/index.js"
    },
    "mainFields": [
      "browser",
      "module",
      "main"
    ]
  },
  "resolveLoader": {
    "modules": [
      "./node_modules",
      "./node_modules"
    ]
  },
  "entry": {
    "main": [
      "./src/main.ts"
    ],
    "polyfills": [
      "./src/polyfills.ts"
    ],
    "styles": [
      "./src/styles.css",
      "./src/assets/global/css/cropper.min.css",
      "./node_modules/video.js/dist/video-js.min.css"
    ]
  },
  "output": {
    "path": path.join(process.cwd(), "../../../public"),
    "filename": "[name].bundle.js",
    "chunkFilename": "[id].chunk.js",
    "crossOriginLoading": false
  },
  "module": {
    "rules": [
      {
        "test": /\.html$/,
        "loader": "raw-loader"
      },
      {
        "test": /\.(eot|svg|cur)$/,
        "loader": "file-loader",
        "options": {
          "name": "[name].[ext]",
          "limit": 10000
        }
      },
      {
        "test": /\.(jpg|png|webp|gif|otf|ttf|woff|woff2|ani)$/,
        "loader": "url-loader",
        "options": {
          "name": "[name].[ext]",
          "limit": 10000
        }
      },
      {
        "test": /\.js$/,
        "use": [
          {
            "loader": "@angular-devkit/build-optimizer/webpack-loader",
            "options": {
              "sourceMap": false
            }
          }
        ]
      },
      {
        "exclude": [
          path.join(process.cwd(), "src/styles.css"),
          path.join(process.cwd(), "src/assets/global/css/cropper.min.css"),
          path.join(process.cwd(), "node_modules/video.js/dist/video-js.min.css")
        ],
        "test": /\.css$/,
        "use": [
          "exports-loader?module.exports.toString()",
          {
            "loader": "css-loader",
            "options": {
              "sourceMap": false,
              "importLoaders": 1
            }
          },
          {
            "loader": "postcss-loader",
            "options": {
              "ident": "postcss",
              "plugins": postcssPlugins
            }
          }
        ]
      },
      {
        "exclude": [
          path.join(process.cwd(), "src/styles.css"),
          path.join(process.cwd(), "src/assets/global/css/cropper.min.css"),
          path.join(process.cwd(), "node_modules/video.js/dist/video-js.min.css")
        ],
        "test": /\.scss$|\.sass$/,
        "use": [
          "exports-loader?module.exports.toString()",
          {
            "loader": "css-loader",
            "options": {
              "sourceMap": false,
              "importLoaders": 1
            }
          },
          {
            "loader": "postcss-loader",
            "options": {
              "ident": "postcss",
              "plugins": postcssPlugins
            }
          },
          {
            "loader": "sass-loader",
            "options": {
              "sourceMap": false,
              "precision": 8,
              "includePaths": []
            }
          }
        ]
      },
      {
        "exclude": [
          path.join(process.cwd(), "src/styles.css"),
          path.join(process.cwd(), "src/assets/global/css/cropper.min.css"),
          path.join(process.cwd(), "node_modules/video.js/dist/video-js.min.css")
        ],
        "test": /\.less$/,
        "use": [
          "exports-loader?module.exports.toString()",
          {
            "loader": "css-loader",
            "options": {
              "sourceMap": false,
              "importLoaders": 1
            }
          },
          {
            "loader": "postcss-loader",
            "options": {
              "ident": "postcss",
              "plugins": postcssPlugins
            }
          },
          {
            "loader": "less-loader",
            "options": {
              "sourceMap": false
            }
          }
        ]
      },
      {
        "exclude": [
          path.join(process.cwd(), "src/styles.css"),
          path.join(process.cwd(), "src/assets/global/css/cropper.min.css"),
          path.join(process.cwd(), "node_modules/video.js/dist/video-js.min.css")
        ],
        "test": /\.styl$/,
        "use": [
          "exports-loader?module.exports.toString()",
          {
            "loader": "css-loader",
            "options": {
              "sourceMap": false,
              "importLoaders": 1
            }
          },
          {
            "loader": "postcss-loader",
            "options": {
              "ident": "postcss",
              "plugins": postcssPlugins
            }
          },
          {
            "loader": "stylus-loader",
            "options": {
              "sourceMap": false,
              "paths": []
            }
          }
        ]
      },
      {
        "include": [
          path.join(process.cwd(), "src/styles.css"),
          path.join(process.cwd(), "src/assets/global/css/cropper.min.css"),
          path.join(process.cwd(), "node_modules/video.js/dist/video-js.min.css")
        ],
        "test": /\.css$/,
        "loaders": ExtractTextPlugin.extract({
  "use": [
    {
      "loader": "css-loader",
      "options": {
        "sourceMap": false,
        "importLoaders": 1
      }
    },
    {
      "loader": "postcss-loader",
      "options": {
        "ident": "postcss",
        "plugins": postcssPlugins
      }
    }
  ],
  "publicPath": ""
})
      },
      {
        "include": [
          path.join(process.cwd(), "src/styles.css"),
          path.join(process.cwd(), "src/assets/global/css/cropper.min.css"),
          path.join(process.cwd(), "node_modules/video.js/dist/video-js.min.css")
        ],
        "test": /\.scss$|\.sass$/,
        "loaders": ExtractTextPlugin.extract({
  "use": [
    {
      "loader": "css-loader",
      "options": {
        "sourceMap": false,
        "importLoaders": 1
      }
    },
    {
      "loader": "postcss-loader",
      "options": {
        "ident": "postcss",
        "plugins": postcssPlugins
      }
    },
    {
      "loader": "sass-loader",
      "options": {
        "sourceMap": false,
        "precision": 8,
        "includePaths": []
      }
    }
  ],
  "publicPath": ""
})
      },
      {
        "include": [
          path.join(process.cwd(), "src/styles.css"),
          path.join(process.cwd(), "src/assets/global/css/cropper.min.css"),
          path.join(process.cwd(), "node_modules/video.js/dist/video-js.min.css")
        ],
        "test": /\.less$/,
        "loaders": ExtractTextPlugin.extract({
  "use": [
    {
      "loader": "css-loader",
      "options": {
        "sourceMap": false,
        "importLoaders": 1
      }
    },
    {
      "loader": "postcss-loader",
      "options": {
        "ident": "postcss",
        "plugins": postcssPlugins
      }
    },
    {
      "loader": "less-loader",
      "options": {
        "sourceMap": false
      }
    }
  ],
  "publicPath": ""
})
      },
      {
        "include": [
          path.join(process.cwd(), "src/styles.css"),
          path.join(process.cwd(), "src/assets/global/css/cropper.min.css"),
          path.join(process.cwd(), "node_modules/video.js/dist/video-js.min.css")
        ],
        "test": /\.styl$/,
        "loaders": ExtractTextPlugin.extract({
  "use": [
    {
      "loader": "css-loader",
      "options": {
        "sourceMap": false,
        "importLoaders": 1
      }
    },
    {
      "loader": "postcss-loader",
      "options": {
        "ident": "postcss",
        "plugins": postcssPlugins
      }
    },
    {
      "loader": "stylus-loader",
      "options": {
        "sourceMap": false,
        "paths": []
      }
    }
  ],
  "publicPath": ""
})
      },
      {
        "test": /(?:\.ngfactory\.js|\.ngstyle\.js|\.ts)$/,
        "use": [
          {
            "loader": "@angular-devkit/build-optimizer/webpack-loader",
            "options": {
              "sourceMap": false
            }
          },
          "@ngtools/webpack"
        ]
      }
    ]
  },
  "plugins": [
    new NoEmitOnErrorsPlugin(),
    new ConcatPlugin({
      "uglify": {
        "sourceMapIncludeSources": true
      },
      "sourceMap": false,
      "name": "scripts",
      "fileName": "[name].bundle.js",
      "filesToConcat": [
        "src/assets/global/js/jquery.min.js",
        "src/assets/global/js/jquery-migrate.min.js",
        "src/assets/global/js/bootstrap.min.js",
        "src/assets/global/js/bootstrap-datepicker.min.js",
        "src/assets/global/js/owl.carousel.min.js",
        "src/assets/global/js/bootstrap-fileinput.js",
        "src/assets/global/plugins/fancybox/source/jquery.fancybox.pack.js",
        "src/assets/global/plugins/slider-revolution-slider/rs-plugin/js/jquery.themepunch.revolution.min.js",
        "src/assets/global/plugins/slider-revolution-slider/rs-plugin/js/jquery.themepunch.tools.min.js",
        "src/assets/global/js/cropper.min.js",
        "src/assets/global/js/jquery.thooClock.js",
        "node_modules/video.js/dist/video.min.js",
        "src/assets/global/js/jquery.vide.min.js"
      ]
    }),
    new InsertConcatAssetsWebpackPlugin([
      "scripts"
    ]),
    new CopyWebpackPlugin([
      {
        "context": "src",
        "to": "",
        "from": {
          "glob": "assets/**/*",
          "dot": true
        }
      }
    ], {
      "ignore": [
        ".gitkeep"
      ],
      "debug": "warning"
    }),
    new ProgressPlugin(),
    new CircularDependencyPlugin({
      "exclude": /(\\|\/)node_modules(\\|\/)/,
      "failOnError": false
    }),
    new HtmlWebpackPlugin({
      "template": "./src/index.html",
      "filename": "./index.html",
      "hash": false,
      "inject": true,
      "compile": true,
      "favicon": false,
      "minify": {
        "caseSensitive": true,
        "collapseWhitespace": true,
        "keepClosingSlash": true
      },
      "cache": true,
      "showErrors": true,
      "chunks": "all",
      "excludeChunks": [],
      "title": "Webpack App",
      "xhtml": true,
      "chunksSortMode": function sort(left, right) {
        let leftIndex = entryPoints.indexOf(left.names[0]);
        let rightindex = entryPoints.indexOf(right.names[0]);
        if (leftIndex > rightindex) {
            return 1;
        }
        else if (leftIndex < rightindex) {
            return -1;
        }
        else {
            return 0;
        }
    }
    }),
    new BaseHrefWebpackPlugin({}),
    new CommonsChunkPlugin({
      "name": [
        "inline"
      ],
      "minChunks": null
    }),
    new CommonsChunkPlugin({
      "name": [
        "main"
      ],
      "minChunks": 2,
      "async": "common"
    }),
    new ExtractTextPlugin({
      "filename": "[name].bundle.css"
    }),
    new SuppressExtractedTextChunksWebpackPlugin(),
    new EnvironmentPlugin({
      "NODE_ENV": "production"
    }),
    new HashedModuleIdsPlugin({
      "hashFunction": "md5",
      "hashDigest": "base64",
      "hashDigestLength": 4
    }),
    new ModuleConcatenationPlugin({}),
    new UglifyJsPlugin({
      "test": /\.js$/i,
      "extractComments": false,
      "sourceMap": false,
      "cache": false,
      "parallel": false,
      "uglifyOptions": {
        "output": {
          "ascii_only": true,
          "comments": false
        },
        "ecma": 5,
        "warnings": false,
        "ie8": false,
        "mangle": true,
        "compress": {
          "pure_getters": true,
          "passes": 3
        }
      }
    }),
    new LicenseWebpackPlugin({
      "licenseFilenames": [
        "LICENSE",
        "LICENSE.md",
        "LICENSE.txt",
        "license",
        "license.md",
        "license.txt"
      ],
      "perChunkOutput": false,
      "outputTemplate": nodeModules + "/license-webpack-plugin/output.template.ejs",
      "outputFilename": "3rdpartylicenses.txt",
      "suppressErrors": true,
      "includePackagesWithoutLicense": false,
      "abortOnUnacceptableLicense": false,
      "addBanner": false,
      "bannerTemplate": "/*! 3rd party license information is available at <%- filename %> */",
      "includedChunks": [],
      "excludedChunks": [],
      "additionalPackages": [],
      "pattern": /^(MIT|ISC|BSD.*)$/
    }),
    new AngularCompilerPlugin({
      "mainPath": "main.ts",
      "platform": 0,
      "hostReplacementPaths": {
        "environments/environment.ts": "environments/environment.prod.ts"
      },
      "sourceMap": false,
      "tsConfigPath": "src/tsconfig.json",
      "compilerOptions": {}
    })
  ],
  "node": {
    "fs": "empty",
    "global": true,
    "crypto": "empty",
    "tls": "empty",
    "net": "empty",
    "process": true,
    "module": false,
    "clearImmediate": false,
    "setImmediate": false
  },
  "devServer": {
    "historyApiFallback": true
  }
};
