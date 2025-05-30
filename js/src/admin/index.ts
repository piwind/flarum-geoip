import app from 'flarum/admin/app';
import GeoipSettingsPage from './components/GeoipSettingsPage';

export * from './components';
export { default as extend } from './extend';

app.initializers.add('piwind/geoip', () => {
  app.extensionData
    .for('piwind-geoip')
    .registerPage(GeoipSettingsPage)
    .registerPermission(
      {
        icon: 'fas fa-globe',
        permission: 'piwind-geoip.canSeeCountry',
        label: app.translator.trans('piwind-geoip.admin.permissions.see_country'),
      },
      'moderate',
      50
    );
});
