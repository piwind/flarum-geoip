import app from 'flarum/admin/app';
import Alert from 'flarum/common/components/Alert';
import ExtensionPage from 'flarum/admin/components/ExtensionPage';
import humanTime from 'flarum/common/helpers/humanTime';
import extractText from 'flarum/common/utils/extractText';
import Mithril from 'mithril';
import ItemList from 'flarum/common/utils/ItemList';
// @ts-expect-error
import linkify from 'linkify-lite';

export default class GeoipSettingsPage extends ExtensionPage {
  content() {
    const service = this.setting('piwind-geoip.service')();
    const errorTime = Number(app.data.settings[`piwind-geoip.services.${service}.last_error_time`]) * 1000;
    const error = app.data.settings[`piwind-geoip.services.${service}.error`] as string | undefined;

    return (
      <div className="GeoipSettingsPage">
        <div className="container">
          <div className="GeoipSettingsTabPage GeoipSettingsPage--settings">
            <div className="Form">
              {error && (
                <Alert className="Form-group" dismissable={false}>
                  <b style={{ textTransform: 'uppercase', marginRight: '5px' }}>{humanTime(new Date(errorTime))}</b>
                  {error}
                </Alert>
              )}
              {this.settingsItems().toArray()}
              <div className="Form-group">{this.submitButton()}</div>
            </div>
          </div>
        </div>
      </div>
    );
  }

  settingsItems(): ItemList<Mithril.Children> {
    const items = new ItemList<Mithril.Children>();

    items.add(
      'general',
      <div className="Section">
        <h3>{app.translator.trans('piwind-geoip.admin.settings.general.heading')}</h3>
        <p className="helpText">{app.translator.trans('piwind-geoip.admin.settings.general.help')}</p>
        {this.generalItems().toArray()}
      </div>
    );

    items.add(
      'providers',
      <div className="Section">
        <h3>{app.translator.trans('piwind-geoip.admin.settings.providers.heading')}</h3>
        <p className="helpText">{app.translator.trans('piwind-geoip.admin.settings.providers.help')}</p>
        {this.providerItems().toArray()}
      </div>
    );

    return items;
  }

  generalItems(): ItemList<Mithril.Children> {
    const items = new ItemList<Mithril.Children>();

    items.add(
      'show-flags',
      this.buildSettingComponent({
        setting: 'piwind-geoip.showFlag',
        type: 'boolean',
        label: app.translator.trans('piwind-geoip.admin.settings.show_flag_label'),
        help: app.translator.trans('piwind-geoip.admin.settings.show_flag_help'),
      })
    );

    return items;
  }

  providerItems(): ItemList<Mithril.Children> {
    const items = new ItemList<Mithril.Children>();
    const service = this.setting('piwind-geoip.service')();

    items.add(
      'service',
      this.buildSettingComponent({
        type: 'select',
        setting: 'piwind-geoip.service',
        label: app.translator.trans('piwind-geoip.admin.settings.service_label'),
        options: (app.data['piwind-geoip.services'] as string[]).reduce((o: { [x: string]: string }, p: string) => {
          o[p] = extractText(app.translator.trans(`piwind-geoip.admin.settings.service_${p}_label`));
          return o;
        }, {}),
        required: true,
        help: service && m.trust(linkify(extractText(app.translator.trans(`piwind-geoip.admin.settings.service_${service}_description`)))),
      })
    );

    ['ipdata', 'ipapi-pro', 'ipsevenex'].includes(service) &&
      items.add(
        'api-key',
        this.buildSettingComponent({
          type: 'string',
          setting: `piwind-geoip.services.${service}.access_key`,
          label: app.translator.trans('piwind-geoip.admin.settings.access_key_label'),
          required: true,
        })
      );

    service === 'ipdata' &&
      items.add(
        'ipdata-quota',
        this.buildSettingComponent({
          type: 'number',
          setting: 'piwind-geoip.services.ipdata.quota',
          label: app.translator.trans('piwind-geoip.admin.settings.quota_label'),
          min: 1500,
          placeholder: 1500,
        })
      );

    return items;
  }
}
