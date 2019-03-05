<?php
namespace verbb\iconpicker\fields;

use verbb\iconpicker\IconPicker;
use verbb\iconpicker\assetbundles\IconPickerAsset;
use verbb\iconpicker\models\IconModel;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\helpers\FileHelper;
use craft\helpers\Json;
use craft\helpers\UrlHelper;

use yii\db\Schema;

class IconPickerField extends Field
{
    // Static
    // =========================================================================

    public static function displayName(): string
    {
        return Craft::t('icon-picker', 'Icon Picker');
    }

    public static function supportedTranslationMethods(): array
    {
        return [];
    }


    // Properties
    // =========================================================================

    public $columnType = Schema::TYPE_TEXT;
    public $showLabels = false;
    public $iconSets;


    // Public Methods
    // =========================================================================

    public function getInputHtml($value, ElementInterface $element = null): string
    {
        if (!$value) {
            $value = new IconModel();
        }

        $id = Craft::$app->getView()->formatInputId($this->handle);
        $nameSpacedId = Craft::$app->getView()->namespaceInputId($id);

        $settings = IconPicker::$plugin->getSettings();

        Craft::$app->getView()->registerAssetBundle(IconPickerAsset::class);

        $iconSets = IconPicker::$plugin->getService()->getIcons($this->iconSets);
        $spriteSheets = IconPicker::$plugin->getService()->getSpriteSheets();
        $fonts = IconPicker::$plugin->getService()->getLoadedFonts();

        Craft::$app->getView()->registerJs('new Craft.IconPicker.Input(' . json_encode([
            'id' => $id,
            'inputId' => $nameSpacedId,
            'name' => $this->handle,
            'fonts' => $fonts,
            'spriteSheets' => $spriteSheets,
            'settings' => $this->settings,
        ]) . ');');

        return Craft::$app->getView()->renderTemplate('icon-picker/_field/input', [
            'id' => $id,
            'name' => $this->handle,
            'namespaceId' => $nameSpacedId,
            'value' => $value,
            'iconSets' => $iconSets,
            'showLabels' => $this->showLabels,
        ]);
    }

    public function getSettingsHtml()
    {
        $settings = IconPicker::getInstance()->getSettings();
        $iconSetsPath = $settings->iconSetsPath;

        $errors = [];

        $iconSets = IconPicker::$plugin->getService()->getIconSets();

        if (!$iconSets) {
            $errors[] = 'Unable to locate SVG Icons source directory.</strong><br>Please ensure the directory <code>' . $iconSetsPath . '</code> exists.</p>';
        }

        return Craft::$app->getView()->renderTemplate('icon-picker/_field/settings', [
            'settings' => $this->getSettings(),
            'iconSets' => $iconSets,
            'errors' => $errors,
        ]);
    }

    public function normalizeValue($value, ElementInterface $element = null)
    {
        $model = new IconModel();            

        if (is_string($value) && !empty($value)) {
            $value = Json::decodeIfJson($value);
        }

        if (is_array($value)) {
            $model->setAttributes($value, false);
        }

        return $model;
    }

    public function serializeValue($value, ElementInterface $element = null)
    {
        // If saving a sprite, we need to sort out the type - although easier than front-end input changing.
        if (strstr($value['icon'], 'sprite:')) {
            $explode = explode(':', $value['icon']);

            $value['icon'] = null;
            $value['type'] = $explode[0];
            $value['iconSet'] = $explode[1];
            $value['sprite'] = $explode[2];
        }

        if (strstr($value['icon'], 'glyph:')) {
            $explode = explode(':', $value['icon']);

            $value['icon'] = null;
            $value['type'] = $explode[0];
            $value['iconSet'] = $explode[1];
            $value['glyphId'] = $explode[2];
            $value['glyphName'] = $explode[3];
        }

        return $value;
    }
}
